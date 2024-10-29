<?php

namespace App\Filament\Resources\ContestResource\Pages;

use App\Filament\Exports\RegistrationExporter;
use App\Filament\Resources\ContestResource;
use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageContestRegistrations extends ManageRelatedRecords
{
    protected static string $resource = ContestResource::class;

    protected static string $relationship = 'registrations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Registrations';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContestResource\Widgets\RegistrationsOverview::class,
        ];
    }

    public function form(Form $form): Form
    {
        return RegistrationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(RegistrationResource::table($table)->getColumns())
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
//                Tables\Actions\ExportAction::make()
//                    ->fileDisk('export-file')
//                    ->exporter(RegistrationExporter::class),
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
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\ExportBulkAction::make()
//                        ->fileDisk('export-file')
//                        ->exporter(RegistrationExporter::class),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
