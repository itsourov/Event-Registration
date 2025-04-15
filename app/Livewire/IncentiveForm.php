<?php

namespace App\Livewire;

use App\Models\Submission;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class IncentiveForm extends Component implements HasForms
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

                TextInput::make('name')
                    ->required(),

                TextInput::make('student_id')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                TextInput::make('semester')
                    ->required(),

                TextInput::make('phone')
                    ->required(),

                Repeater::make('courses')
                    ->extraAttributes([
                        'class' => 'sourov',
                    ])
                    ->schema([
                        TextInput::make('teacher_name')->required(),
                        TextInput::make('teacher_initial')->required(),
                        TextInput::make('course_name')->required(),
                        TextInput::make('course_code')->required(),

                    ])

            ])
            ->statePath('data');
    }

    public function create(): void
    {
        Submission::create($this->form->getState());
        $this->form->fill();
        Notification::make()
            ->title("Submission Created")
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.incentive-form');
    }
}
