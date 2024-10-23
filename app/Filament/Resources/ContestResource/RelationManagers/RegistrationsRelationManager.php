<?php

namespace App\Filament\Resources\ContestResource\RelationManagers;

use App\Filament\Resources\RegistrationResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
