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
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
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
       return RegistrationResource::table($table);
    }

    public function getTitle(): string
    {
        // Retrieve the related Contest record and set the title based on its name
        $contest = $this->getOwnerRecord();

        return $contest ? "{$contest->name} - Registrations" : 'Registrations';
    }
}
