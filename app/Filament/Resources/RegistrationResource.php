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
                Placeholder::make('timestamp')
                    ->content(fn(?Registration $record): string => $record?->created_at?->format('d/m/Y h:i:s') ?? '-'),
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
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time Stamp')
                    ->sortable(),
                TextColumn::make('name')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('student_id')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->toggleable(),
                TextColumn::make('payment_transaction_id')
                    ->label('Transaction ID')
                    ->toggleable(),
                TextColumn::make('payment_phone')
                    ->label('Payment Phone')
                    ->toggleable(),
                TextColumn::make('status')
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('phone')
                    ->toggleable(),

                TextColumn::make('contest.name')
                    ->toggledHiddenByDefault(false)
                    ->toggleable(),


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
