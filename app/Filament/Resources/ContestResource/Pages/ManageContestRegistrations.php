<?php

namespace App\Filament\Resources\ContestResource\Pages;

use App\Enums\RegistrationStatuses;
use App\Filament\Exports\RegistrationExporter;
use App\Filament\Resources\ContestResource;
use App\Filament\Resources\RegistrationResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ManageContestRegistrations extends ManageRelatedRecords
{
    protected static string $resource = ContestResource::class;

    protected static string $relationship = 'registrations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getNavigationLabel(): string
    {
        return 'Registrations';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContestResource\Widgets\RegistrationsOverview::class,
            ContestResource\Widgets\ContstRegistrationChart::class,
        ];
    }

    public function form(Form $form): Form
    {
        return RegistrationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(RegistrationResource::table($table)->getColumns())
            ->filters([
                SelectFilter::make('status')
                    ->options(RegistrationStatuses::class),
                SelectFilter::make('section')
                    ->label('Section')
                    ->options(function () use ($table) {
                        return $table->getQuery()->pluck('section')
                            ->filter(fn($section) => !is_null($section) && $section !== '')
                            ->unique()->mapWithKeys(fn($section) => [$section => $section]);

                    }),
            ])
            ->headerActions([
               CreateAction::make(),
                ExportAction::make()
                    ->fileDisk('export-file')
                    ->exporter(RegistrationExporter::class),
            ])
            ->paginated([10, 25, 50])
            ->actions([
                ActivityLogTimelineTableAction::make('Activities'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()
                        ->label("export Data")
                        ->fileDisk('export-file')
                        ->exporter(RegistrationExporter::class),
                ]),
            ]);
    }

    public function getTitle(): string
    {
        // Retrieve the related Contest record and set the title based on its name
        $contest = $this->getOwnerRecord();

        return $contest ? "{$contest->name} - Registrations" : 'Registrations';
    }
}
