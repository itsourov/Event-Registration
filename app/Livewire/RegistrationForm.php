<?php

namespace App\Livewire;

use App\Enums\RegistrationStatuses;
use App\Models\Contest;
use App\Models\Registration;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;

;

use Filament\Forms\Components\Wizard;


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
                Wizard::make([
                    Wizard\Step::make('Basic Information')
                        ->columns(['sm' => 2])
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->default(auth()->user()?->email)
                                ->suffixIcon('heroicon-o-at-symbol')
                                ->disabled()
                                ->required(),
                            TextInput::make('student_id')
                                ->placeholder('xxx-xx-xxx')
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
                            Select::make('lab_teacher_name')
                                ->options($this->getLabTeacherOptions())
                                ->required(),
                            Placeholder::make('T-shirt Sizes')
                                ->content(
                                    fn() => new HtmlString($this->contest->getFirstMedia('tshirt-sizes')?->img())
                                ),
                            Select::make('tshirt_size')
                                ->options($this->getTShirtSizeOptions())
                                ->required(),


                        ]),
                    Wizard\Step::make('Extra')
                        ->columns(['sm' => 2])
                        ->schema([
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
                        ]),
                    Wizard\Step::make('Payment')
                        ->columns(['sm' => 2])
                        ->schema([
                            ToggleButtons::make('payment_method')
                                ->columnSpan(['sm' => 2])
                                ->inline()
                                ->reactive()
                                ->required()
                                ->options($this->getManualPaymentOptions()),
                            Placeholder::make('instructions')
                                ->columnSpan(['sm' => 2])
                                ->content(fn() => new HtmlString($this->getSelectedPaymentInfo())),
                            TextInput::make('payment_phone')
                                ->label(($this->data['payment_method'] ?? "") . ' Mobile Number (Used for Payment)')
                                ->numeric()
                                ->minLength(11)
                                ->maxLength(12)
                                ->prefix('+88')
                                ->suffixIcon('heroicon-o-phone')
                                ->required(),
                            TextInput::make('payment_transaction_id')
                                ->label(($this->data['payment_method'] ?? "") . ' Transaction ID')
                                ->required(),

                        ]),

                ])
                    ->registerListeners([
                        'wizard::nextStep' => [
                            function (Wizard $component, string $statePath, int $currentStepIndex): void {

                                if ($statePath !== $component->getStatePath()) {
                                    return;
                                }


                                // Validation rules for each step
                                if ($currentStepIndex == 0) {
                                    // Step 1: Basic Information
                                    $this->validate([
                                        'data.student_id' => [$this->contest->student_id_rules],
                                    ], [
                                        'data.student_id.regex' => $this->contest->student_id_rules_guide,
                                    ]);
                                }


                            },
                        ],
                    ])
                    ->skippable()
                    ->submitAction(view('registration.form-submit-button'))
            ])
            ->model(Registration::class)
            ->statePath('data');
    }

    public function create(): void
    {
        $user = auth()->user();
        if (!$this->hasRegistration)
            activity()->disableLogging();

        // Validate input
        $data = $this->validate([
            'data.student_id' => [$this->contest->student_id_rules],
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
            array_merge($formData, ['email' => $user?->email, 'status' => RegistrationStatuses::PENDING])
        );

        // Update the registration status and show success notification
        $this->hasRegistration = true;
        Notification::make()
            ->title("Information Saved!")
            ->success()
            ->send();
        $this->redirect(route('contests.registration.myRegistration', $this->contest));
    }


    public function payNow(): void
    {
        $this->create();
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

    private function getManualPaymentOptions(): array
    {
        return array_combine(
            array_column($this->contest->manual_payment_methods, 'name'),
            array_column($this->contest->manual_payment_methods, 'name')
        );
    }

    public function getSelectedPaymentInfo()
    {
        $selectedMethod = $this->data['payment_method'] ?? null;

        return collect($this->contest->manual_payment_methods)
            ->firstWhere('name', $selectedMethod)['info'] ?? 'Please select a payment method to see the instructions.';
    }

}
