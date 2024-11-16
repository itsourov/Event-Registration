<?php

namespace App\Filament\Resources\ContestResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class ContstRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Sections';
    public ?Model $record = null;

    protected function getData(): array
    {
        // Get the count of each section in the Registration model
        $sectionCounts = Registration::where('contest_id', $this->record->id)
            ->get()
            ->groupBy(function ( $registration) {
                // Group sections by their main category (e.g., A, B, etc.)
                return preg_replace('/\d+$/', '', $registration->section);
            })
            ->map(fn($group) => $group->count())
            ->sortKeys() // Sorts by section name (key)
            ->toArray();

        // Prepare the labels and data for the chart
        $labels = array_keys($sectionCounts);
        $data = array_values($sectionCounts);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Sections',
                    'data' => $data,
                    'backgroundColor' => '#4ade80', // Customize bar color
                ],
            ],
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 2;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
