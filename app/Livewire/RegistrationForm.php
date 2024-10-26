<?php

namespace App\Livewire;

use App\Models\Contest;
use App\Models\Registration;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Exception;

class RegistrationForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Contest $contest;

    public ?array $data = [];
    public bool $hasRegistration = false;


    public function mount(Contest $contest): void
    {

        $this->contest = $contest;
        $registration = Registration::where('user_id', auth()->user()->id)->where('contest_id', $this->contest->id)->first();
        if ($registration) {
            $this->hasRegistration = true;
        }
        $this->form->fill($registration?->toArray());

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Registration Form")
                    ->description($this->contest->name)
//                    ->extraAttributes(['style' => 'background-color:#fdfee9'])
                    ->columns(['sm' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->default(auth()->user()?->name)
                            ->required(),
                        TextInput::make('email')
                            ->default(auth()->user()?->email)
                            ->suffixIcon('heroicon-o-at-symbol')
                            ->disabled()
                            ->required(),
                        TextInput::make('student_id')
                            ->placeholder('232-15-000')
                            ->required(),
                        TextInput::make('phone')
                            ->numeric()
                            ->length(11)
                            ->prefix('+88')
                            ->suffixIcon('heroicon-o-phone')
                            ->required(),
                        Select::make('department')
                            ->options($this->getDepartmentOptions())
                            ->default('CSE')
                            ->required(),
                        Select::make('section')
                            ->options($this->getSectionOptions())
                            ->required(),
                        Select::make('gender')
                            ->options($this->getGenderOptions())
                            ->required(),
                        Select::make('tshirt_size')
                            ->options($this->getTShirtSizeOptions())
                            ->required(),
                        Select::make('transportation_service')
                            ->options([
                                'Yes' => 'Yes',
                                'No' => 'No',
                            ])
                            ->reactive()
                            ->required(),
                        Select::make('pickup_point')
                            ->options($this->getPickupPoints())
                            ->visible(fn(callable $get) => $get('transportation_service') === 'Yes')
                            ->required(),
                        Select::make('lab_teacher_name')
                            ->options($this->getLabTeacherOptions())
                            ->required(),
                    ]),
            ])
            ->model(Registration::class)
            ->statePath('data');
    }

    public function create(): void
    {
        $user = auth()->user();

        // Validate input
        $data = $this->validate([
            'data.student_id' => $this->contest->student_id_rules,
        ], [
            'data.student_id.regex' => $this->contest->student_id_rules_guide,
        ]);

        // Check if transportation_service is "No" and clear pickup_point if needed
        $formData = $this->form->getState();
        if ($formData['transportation_service'] === 'No') {
            $formData['pickup_point'] = null; // Clear pickup_point if transportation_service is "No"
        }

        // Save to the database
        Registration::updateOrCreate(
            [
                'user_id' => $user->id,
                'contest_id' => $this->contest->id,
            ],
            array_merge($formData, ['email' => $user?->email])
        );

        // Update the registration status and show success notification
        $this->hasRegistration = true;
        Notification::make()
            ->title("Information Saved!")
            ->success()
            ->send();
    }


    public function payNow(): void
    {
        $registration = Registration::where('user_id', auth()->user()->id)->where('contest_id', $this->contest->id)->first();

        if ($registration)
            redirect()->route('registration.payment.create', $registration, [
                'redirect_url' => route('contests.registration.myRegistration', $this->contest),
            ]);
        else {
            Notification::make()
                ->title("Registration Not Found!")
                ->warning()
                ->send();
        }
    }

    public function render()
    {
        if (auth()->user()->registration) {
            $this->data = auth()->user()->registration->toArray();
        }
        return view('livewire.registration-form');
    }


    private function getDepartmentOptions(): array
    {
        return array_combine(
            array_column($this->contest->departments, 'name'),
            array_column($this->contest->departments, 'name')
        );
    }

    private function getPickupPoints(): array
    {
        $pickupPoints = $this->contest->pickup_points ?? []; // Default to empty array if null

        return array_combine(
            array_column($pickupPoints, 'name'),
            array_column($pickupPoints, 'name')
        ) ?: []; // Return an empty array if array_combine fails
    }


    private function getSectionOptions(): array
    {
        return array_combine(
            array_column($this->contest->sections, 'name'),
            array_column($this->contest->sections, 'name')
        );
    }

    private function getGenderOptions(): array
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other',
        ];
    }

    private function getTShirtSizeOptions(): array
    {
        return ['M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL', 'XXXL' => 'XXXL'];
    }

    private function getLabTeacherOptions(): array
    {
        $labTeachers = $this->contest->lab_teacher_names;

        // Use array_map to transform each lab teacher's data
        $formattedTeachers = array_map(function ($teacher) {
            $formattedName = "{$teacher['full_name']} ({$teacher['initial']})";
            return [$formattedName => $formattedName];
        }, $labTeachers);

        // Merge all formatted entries into a single array
        return array_merge(...$formattedTeachers);
    }

}
