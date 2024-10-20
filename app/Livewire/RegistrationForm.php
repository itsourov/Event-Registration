<?php

namespace App\Livewire;

use App\Models\Registration;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class RegistrationForm extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Registration Form")
                    ->extraAttributes(['style' => 'background-color:#fdfee9'])
                    ->columns([
                        'sm' => 2,

                    ])
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
                            ->options([
                                'CSE' => 'CSE',
                                'CIS' => 'CIS'
                            ])
                            ->in(fn(Select $component): array => array_keys($component->getEnabledOptions()))
                            ->default('CSE')
                            ->required(),
                        Select::make('section')
                            ->in(fn(Select $component): array => array_keys($component->getEnabledOptions()))
                            ->options(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'])
                            ->required(),
                        Select::make('lab_teacher_name')
                            ->in(fn(Select $component): array => array_keys($component->getEnabledOptions()))
                            ->options(['Person 1', 'Person 2', 'Person 3', 'Person 4', 'Person 5', 'Person 6', 'Other'])
                            ->required(),

                        Select::make('tshirt_size')
                            ->in(fn(Select $component): array => array_keys($component->getEnabledOptions()))
                            ->options(['M', 'L', 'XL', 'XXL', 'XXXL'])
                            ->required(),
                        Select::make('gender')
                            ->in(fn(Select $component): array => array_keys($component->getEnabledOptions()))
                            ->options(['Male', 'Female'])
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {

        if (auth()->user()->registration) {
            auth()->user()->registration->update($this->form->getState());
        } else {
            auth()->user()->registration()->updateOrCreate($this->form->getState());
        }
        Notification::make()
            ->title("Information Saved!")
            ->success()
            ->send();


    }

    public function render()
    {
        if (auth()->user()->registration) {
            $this->data = auth()->user()->registration?->toArray();
        }
        return view('livewire.registration-form');
    }
}
