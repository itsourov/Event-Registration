<?php

namespace App\Filament\Pages;

use App\Filament\Exports\RegistrationExporter;
use App\Filament\Resources\RegistrationResource;
use App\Models\Contest;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Symfony\Component\DomCrawler\Crawler;

class StandingsFilter extends Page implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public ?array $data = [];
    public ?array $standingsData = [];
    public array $filteredIDs = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.standings-filter';


    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('page_StandingsFilter');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('process')
                ->label('Process')
                ->submit('process'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(['sm' => 2])
                    ->schema([
                        TextInput::make('standings_url')
                            ->default('https://toph.co/c/diu-take-off-fall-24-preliminary-b-slot/standings')
                            ->placeholder('Standings URL')
                            ->required(),
                        Select::make('contest_id')
                            ->label('Contest')
                            ->options(Contest::pluck('name', 'id'))
                            ->required()
                            ->reactive(), // Make it reactive to trigger visibility changes
                        TextInput::make('rank_from')
                            ->numeric(),
                        TextInput::make('rank_to')
                            ->numeric(),
                        Select::make('genders')
                            ->label('Genders')
                            ->options(function (callable $get) {
                                $contestId = $get('contest_id');
                                if ($contestId) {

                                    return cache()->remember(
                                        'genders_of_contest_' . $contestId,
                                        7200,
                                        fn() => Registration::where('contest_id', $contestId)
                                            ->pluck('gender')
                                            ->unique()
                                            ->filter()
                                            ->values()
                                            ->mapWithKeys(fn($gender) => [$gender => ucfirst($gender)])
                                            ->toArray()
                                    );
                                    // Fetch unique genders from the Registration model for the selected contest

                                }
                                return [];
                            })
                            ->multiple()
                            ->hidden(fn(callable $get) => !$get('contest_id')), // Only show when a contest is selected

//                        Toggle::make('female_only')->label('Female Only'),
                    ]),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getFilteredRegistrations())
            ->paginated([10, 25, 50])
            ->recordUrl(
                fn(Model $record): string => route('filament.admin.resources.registrations.edit', $record),
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('Rank')
                    ->getStateUsing(function ($record) {
                        // Map the rank from standingsData based on student_id
                        $rank = collect($this->standingsData)
                            ->firstWhere('student_id', $record->student_id)['rank'] ?? 'N/A';
                        return $rank;
                    }),
                TextColumn::make('name')->toggleable()->searchable(),
                TextColumn::make('student_id')->label('Student ID')->toggleable()->searchable(),
                TextColumn::make('section')->toggleable(),
                TextColumn::make('department')->toggleable(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->fileDisk('export-file')
                    ->exporter(RegistrationExporter::class),
            ])
            ->actions([
                ActivityLogTimelineTableAction::make('Activities'),
            ]) // Add if needed
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label("export Data")
                        ->fileDisk('export-file')
                        ->exporter(RegistrationExporter::class),
                ]),
            ]); // Add if needed
    }

    private function getFilteredRegistrations()
    {
        return Registration::query()
            ->where('contest_id', $this->data['contest_id'] ?? 0)
            ->when(!empty($this->filteredIDs), fn($query) => $query->whereIn('student_id', $this->filteredIDs))
            ->when(empty($this->filteredIDs), fn($query) => $query->whereIn('student_id', []));
    }

    public function process(): void
    {
        try {
            $this->form->getState();

            $registrations = Registration::where('contest_id', $this->data['contest_id'])->get();

            $rankList = cache()->remember(
                'standings_' . $this->data['contest_id'] . $this->data['standings_url'],
                7200,
                fn() => $this->fetchRankList($this->data['standings_url'])
            );

            $this->standingsData = [];
            foreach ($rankList as $rank) {
                if ($this->shouldSkipRank($rank)) continue;

                $registration = $registrations->where('student_id', $rank['id'])->first();

                if ($this->data['genders'] && !in_array(($registration->gender ?? "N/A"), $this->data['genders'])) {
                    continue;
                }

                $this->standingsData[] = [
                    'rank' => $rank['rank'],
                    'name' => $rank['name'],
                    'student_id' => $registration->student_id ?? 'Not Found',
                    'gender' => $registration->gender ?? 'Not Found',
                ];
            }

            $this->filteredIDs = array_column($this->standingsData, 'student_id');
            $this->resetTable();
            Notification::make()
                ->title("Processing Success")
                ->body("Found " . count($this->filteredIDs) . " IDs.")
                ->success()
                ->send();
        } catch (\Exception $exception) {
            // Handle exceptions gracefully
        }
    }

    private function shouldSkipRank(array $rank): bool
    {
        return ($this->data['rank_from'] && $rank['rank'] < $this->data['rank_from']) ||
            ($this->data['rank_to'] && $rank['rank'] > $this->data['rank_to']);
    }

    private function fetchRankList(string $url): array
    {
        $client = new Client();
        $rankList = [];
        $start = 0;

        while (true) {
            $htmlContent = cache()->remember($url . '?start=' . $start, 7200, fn() => $client->get($url . '?start=' . $start)->getBody()->getContents()
            );

            $crawler = new Crawler($htmlContent);
            $rows = $crawler->filter('table tbody tr');

            if ($rows->count() === 0) break;

            $start += $rows->count();

            $rows->each(function (Crawler $row) use (&$rankList) {
                $rank = $row->filter('td:nth-child(1)')->text();
                $secondTd = $row->filter('td:nth-child(2)');
                $name = trim($secondTd->getNode(0)->childNodes[0]->nodeValue);
                $id = $secondTd->filter('.adjunct')->text();
                $details = explode(',', $id);

                $rankList[] = [
                    'rank' => $rank,
                    'name' => $name,
                    'id' => trim($details[0]),
                    'section' => trim($details[1] ?? ''),
                    'department' => trim($details[2] ?? ''),
                ];
            });
        }

        return $rankList;
    }
}
