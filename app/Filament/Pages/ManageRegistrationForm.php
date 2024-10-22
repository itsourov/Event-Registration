<?php

namespace App\Filament\Pages;

use App\Settings\RegistrationFormSettings;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageRegistrationForm extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = RegistrationFormSettings::class;
    protected static ?string $navigationGroup = 'Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Lab Teacher Info')
                    ->description("Option value for registration forms")
                    ->schema([
                        Toggle::make('lab_teacher_names_enabled'),


                        Repeater::make('lab_teacher_names')
                            ->grid(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('initial')->required(),
                                TextInput::make('full_name')
                                    ->required(),
                            ]),

                    ])
                ,
                 Section::make('Department & Sections')
                     ->description("Option value for registration forms")
                     ->schema([


                         Repeater::make('departments')
                             ->grid(3)
                             ->schema([
                                 TextInput::make('name')->required(),
                             ]),
                         Repeater::make('sections')
                             ->grid(3)
                             ->schema([
                                 TextInput::make('name')->required(),
                             ]),

                     ])
            ]);
    }
}
