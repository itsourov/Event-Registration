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
                                ->options($this->getSectionOptionsWithOther())
                                ->reactive()
                                ->required(),
                            TextInput::make('custom_section')
                                ->hint("Sample section name '69_A'")
                                ->label('Enter your section')
                                ->placeholder('Enter your section name')
                                ->required()
                                ->visible(fn(callable $get) => $get('section') === 'Other'),
                            Select::make('gender')
                                ->options($this->getGenderOptions())
                                ->required(),
                            Select::make('lab_teacher_name')
                                ->options($this->getLabTeacherOptionsWithOther())
                                ->reactive()
                                ->required(),
                            TextInput::make('custom_lab_teacher_name')
                                ->label('Enter lab teacher name')
                                ->placeholder('Enter your lab teacher\'s name')
                                ->required()
                                ->visible(fn(callable $get) => $get('lab_teacher_name') === 'Other'),
                            Placeholder::make('T-shirt Sizes')
                                ->content(
                                    fn() => new HtmlString('<img src="' . $this->contest->getFirstMediaUrl('tshirt-sizes') . '" alt="" />')
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
                                ->options($this->getPickupPointsWithOther())
                                ->reactive()
                                ->visible(fn(callable $get) => $get('transportation_service') === 'Yes')
                                ->required(),
                            TextInput::make('custom_pickup_point')
                                ->label('Enter pickup point')
                                ->placeholder('Enter your preferred pickup location')
                                ->visible(fn(callable $get) => $get('transportation_service') === 'Yes' && $get('pickup_point') === 'Other')
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
                                    $this->data['student_id'] = trim($this->data['student_id']);

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

        // Get form data
        $formData = $this->form->getState();

        // Handle custom section if "Other" is selected
        if ($formData['section'] === 'Other' && isset($formData['custom_section'])) {
            $formData['section'] = $formData['custom_section'];
            unset($formData['custom_section']);
        }

        // Handle custom lab teacher if "Other" is selected
        if ($formData['lab_teacher_name'] === 'Other' && isset($formData['custom_lab_teacher_name'])) {
            $formData['lab_teacher_name'] = $formData['custom_lab_teacher_name'];
            unset($formData['custom_lab_teacher_name']);
        }

        // Handle transportation service and custom pickup point
        if ($formData['transportation_service'] === 'No') {
            $formData['pickup_point'] = null;
        } else if ($formData['pickup_point'] === 'Other' && isset($formData['custom_pickup_point'])) {
            $formData['pickup_point'] = $formData['custom_pickup_point'];
            unset($formData['custom_pickup_point']);
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

    private function getSectionOptionsWithOther(): array
    {
        $sections = array_combine(
            array_column($this->contest->sections, 'name'),
            array_column($this->contest->sections, 'name')
        );

        // Add "Other" option to the sections array
        $sections['Other'] = 'Other';

        return $sections;
    }

    private function getLabTeacherOptionsWithOther(): array
    {
        $labTeachers = $this->contest->lab_teacher_names;

        // Use array_map to transform each lab teacher's data
        $formattedTeachers = array_map(function ($teacher) {
            $formattedName = "{$teacher['full_name']} ({$teacher['initial']})";
            return [$formattedName => $formattedName];
        }, $labTeachers);

        // Merge all formatted entries into a single array
        $options = array_merge(...$formattedTeachers);

        // Add "Other" option
        $options['Other'] = 'Other';

        return $options;
    }

    private function getPickupPointsWithOther(): array
    {
        $pickupPoints = $this->contest->pickup_points ?? []; // Default to empty array if null

        $options = !empty($pickupPoints) ? array_combine(
            array_column($pickupPoints, 'name'),
            array_column($pickupPoints, 'name')
        ) : [];

        // Add "Other" option
        $options['Other'] = 'Other';

        return $options;
    }

    private function getDepartmentOptions(): array
    {
        return array_combine(
            array_column($this->contest->departments, 'name'),
            array_column($this->contest->departments, 'name')
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
        return ['S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL', 'XXXL' => 'XXXL'];
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
