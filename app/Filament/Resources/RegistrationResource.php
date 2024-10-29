<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatuses;
use App\Filament\Exports\RegistrationExporter;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

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
                Textarea::make('extra')->columnSpan([
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
            ->headerActions([
                ExportAction::make()
                    ->fileDisk('export-file')
                    ->exporter(RegistrationExporter::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()
                        ->label("export Data")
                        ->fileDisk('export-file')
                        ->exporter(RegistrationExporter::class),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRegistrations::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
