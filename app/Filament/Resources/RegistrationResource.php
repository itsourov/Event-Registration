<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatuses;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Novadaemon\FilamentPrettyJson\PrettyJson;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $slug = 'registrations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                TextInput::make('student_id')
                    ->required(),

                TextInput::make('phone')
                    ->required(),

                TextInput::make('section'),

                TextInput::make('department'),

                TextInput::make('lab_teacher_name'),

                TextInput::make('tshirt_size'),

                TextInput::make('gender')
                    ->required(),
                Select::make('transportation_service')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ])
                    ->required(),
                TextInput::make('pickup_point')
                    ->nullable(),
                ToggleButtons::make('status')
                    ->live()
                    ->inline()
                    ->options(RegistrationStatuses::class)
                    ->required(),

                TextInput::make('payment_method')
                    ->required(),
                TextInput::make('payment_phone')
                    ->required(),
                TextInput::make('payment_transaction_id')
                    ->required(),
                Select::make('user_id')
                    ->preload()
                    ->searchable()
                    ->relationship('user', 'email')
                    ->required(),

                Select::make('contest_id')
                    ->preload()
                    ->searchable()
                    ->relationship('contest', 'name')
                    ->required(),
                PrettyJson::make('extra')->columnSpan([
                    'md' => 2
                ]),


                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Registration $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Registration $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student_id'),

                TextColumn::make('phone'),
                TextColumn::make('contest.name'),


                TextColumn::make('status'),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
