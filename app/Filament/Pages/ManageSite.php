<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSite extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = SiteSettings::class;
    protected static ?string $navigationGroup = 'Settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_ManageSite');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site Info')
                    ->columns(2)
                    ->schema([

                        TextInput::make('site_title')
                            ->required(),
                        TextInput::make('site_tagline')
                            ->required(),
                    ]),
                Section::make('Contest info')
                    ->columns(2)
                    ->schema([

                        TextInput::make('contest_name')
                            ->required(),
                        TextInput::make('semester')
                            ->required(),
                        TextInput::make('registration_fee')
                            ->numeric()
                            ->required(),
                        DateTimePicker::make('registration_deadline')
                            ->seconds(false)
                            ->required(),

                        DateTimePicker::make('preliminary_date')
                            ->seconds(false)
                            ->required(),
                        DateTimePicker::make('final_date')
                            ->seconds(false)
                            ->required(),
                    ]),
                Section::make('Support Information')
                    ->columns(2)
                    ->schema([

                        TextInput::make('support_email')
                            ->required(),
                        TextInput::make('support_phone')
                            ->required(),

                    ]),
                Section::make('Countdown information')
                    ->description("Homepage Countdown Setting")
                    ->columns(2)
                    ->schema([

                        TextInput::make('countdown_text')
                            ->required(),
                        DateTimePicker::make('countdown_time')
                            ->seconds(false)
                            ->required(),

                    ]),

            ]);
    }
}
