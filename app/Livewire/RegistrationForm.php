<?php

namespace App\Livewire;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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
                        TextInput::make('phone_number')
                            ->numeric()
                            ->length(11)
                            ->prefix('+88')
                            ->suffixIcon('heroicon-o-phone')
                            ->required(),
                        Select::make('department')
                            ->options([
                                'CSE'
                            ])
                            ->default('CSE')
                            ->required(),
                        Select::make('section')
                            ->searchable()
                            ->options(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'])
                            ->required(),
                        Select::make('lab_teacher_name')
                            ->searchable()
                            ->options(['Person 1', 'Person 2', 'Person 3', 'Person 4', 'Person 5', 'Person 6', 'Other'])
                            ->required(),

                        Select::make('t-shirt_size')
                            ->options(['M', 'L', 'XL', 'XXL', 'XXXL'])
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}
