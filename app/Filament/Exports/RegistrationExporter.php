<?php

namespace App\Filament\Exports;

use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegistrationExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')
            ->label("Timestamp"),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('student_id'),
            ExportColumn::make('phone'),
            ExportColumn::make('section'),
            ExportColumn::make('department'),
            ExportColumn::make('lab_teacher_name'),
            ExportColumn::make('tshirt_size'),
            ExportColumn::make('transportation_service'),
            ExportColumn::make('pickup_point'),
            ExportColumn::make('gender'),
            ExportColumn::make('payment_method'),
            ExportColumn::make('payment_phone'),
            ExportColumn::make('payment_transaction_id'),
            ExportColumn::make('status')
                ->getStateUsing(fn ($record) => $record->status->getLabel()),
            ExportColumn::make('contest_id'),
            ExportColumn::make('user.id')
                ->label("User id"),
            ExportColumn::make('extra')
                ->listAsJson(),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your registration export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
