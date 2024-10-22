<?php

namespace App\Livewire;

use App\Settings\RegistrationFormSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Exception;

class RegistrationForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $registrationFormSettings;
    public ?array $data = [];


    public function mount(RegistrationFormSettings $registrationFormSettings): void
    {
        $this->registrationFormSettings = $registrationFormSettings->toArray();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Registration Form")
                    ->extraAttributes(['style' => 'background-color:#fdfee9'])
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
                            ->rules(['regex:/^[0-9-]+$/'])
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
                        Select::make('lab_teacher_name')
                            ->visible($this->registrationFormSettings['lab_teacher_names_enabled'] ?? false)
                            ->options($this->getLabTeacherOptions())
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $user = auth()->user();
        $user->registration
            ? $user->registration->update($this->form->getState())
            : $user->registration()->updateOrCreate(array_merge($this->form->getState(), ['email' => $user?->email]));

        Notification::make()
            ->title("Information Saved!")
            ->success()
            ->send();
        if (!$user->registration)
            redirect()->route('registration.create');

    }

    public function payNow()
    {
        if (auth()->user()->registration)
            redirect()->route('registration.payment.create', auth()->user()->registration);
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
            array_column($this->registrationFormSettings['departments'] ?? [], 'name'),
            array_column($this->registrationFormSettings['departments'] ?? [], 'name')
        );
    }

    private function getSectionOptions(): array
    {
        return array_combine(
            array_column($this->registrationFormSettings['sections'] ?? [], 'name'),
            array_column($this->registrationFormSettings['sections'] ?? [], 'name')
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
        $labTeachers = $this->registrationFormSettings['lab_teacher_names'] ?? [];
        return array_combine(
            array_column($labTeachers, 'initial'),
            array_column($labTeachers, 'full_name')
        );
    }
}
