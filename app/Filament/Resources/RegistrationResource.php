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
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

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
                    ->action(Action::make('update_status')
                        ->form([
                            Placeholder::make('payment_method')
                                ->inlineLabel()
                                ->content(fn(Registration $record) => $record->payment_method),
                            Placeholder::make('payment_phone')
                                ->inlineLabel()
                                ->content(fn(Registration $record) => $record->payment_phone),
                            Placeholder::make('payment_transaction_id')
                                ->inlineLabel()
                                ->content(fn(Registration $record) => $record->payment_transaction_id),
                            ToggleButtons::make('status')
                                ->options(RegistrationStatuses::class)
                                ->default(fn(Registration $record) => $record->status)
                                ->inline()
                                ->required(),
                        ])
                        ->action(function (array $data, Registration $record): void {
                            $record->status = $data['status'];
                            $record->save();

                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }))
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

                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        TextConstraint::make('email'),
                        TextConstraint::make('student_id'),
                        TextConstraint::make('phone'),
                        TextConstraint::make('section'),
                        TextConstraint::make('department'),
                        TextConstraint::make('lab_teacher_name'),
                        TextConstraint::make('tshirt_size'),
                        TextConstraint::make('gender'),
                        TextConstraint::make('transportation_service'),
                        TextConstraint::make('pickup_point'),
                        TextConstraint::make('payment_method'),
                        TextConstraint::make('payment_phone'),
                        TextConstraint::make('payment_transaction_id'),
                        SelectConstraint::make('status')
                            ->options(RegistrationStatuses::class)
                            ->multiple(),

                    ]),
                TrashedFilter::make(),
            ],layout: FiltersLayout::AboveContent)
            ->headerActions([
                ExportAction::make()
                    ->fileDisk('export-file')
                    ->exporter(RegistrationExporter::class),
            ])
            ->paginated([10, 25, 50])
            ->actions([
                ActivityLogTimelineTableAction::make('Activities'),
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

    public static function getRelations(): array
    {
        return [
            ActivitylogRelationManager::class,
        ];
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
}
