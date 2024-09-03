<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\FuelLog;
use App\Models\BloodAssistance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DailyAmountsWidget2 extends ChartWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Daily Amounts for Fuel Logs and Blood Assistance';
    protected static ?int $sort = 3;
   
    protected function getData(): array
    {
        // Retrieve start and end dates from filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Prepare queries for each model
        $models = [
            'Fuel Log' => FuelLog::select(DB::raw('DATE(date) as date'), DB::raw('SUM(total_amount) as total_amount'))
                ->groupBy(DB::raw('DATE(date)'))
                ->orderBy('date'),
            'Blood Assistance' => BloodAssistance::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total_amount'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date'),
        ];

        // Apply date filters to each query
        foreach ($models as $key => $query) {
            if ($startDate) {
                $models[$key]->whereDate('date', '>=', Carbon::parse($startDate)->startOfDay());
            }

            if ($endDate) {
                $models[$key]->whereDate('date', '<=', Carbon::parse($endDate)->endOfDay());
            }
        }

        // Get data for each model
        $data = [];
        foreach ($models as $label => $query) {
            $data[$label] = $query->get();
        }

        // Prepare labels and datasets
        $chartLabels = [];
        foreach ($data as $modelData) {
            foreach ($modelData as $item) {
                if (!in_array($item->date, $chartLabels)) {
                    $chartLabels[] = $item->date;
                }
            }
        }
        sort($chartLabels); // Ensure labels are sorted

        $colors = [
            'Fuel Log' => '#FF5722',         // Orange
            'Blood Assistance' => '#4CAF50', // Green
        ];

        $datasets = [];
        foreach ($data as $label => $modelData) {
            $dataset = [
                'label' => $label,
                'data' => array_map(fn($date) => $modelData->where('date', $date)->sum('total_amount'), $chartLabels),
                'borderColor' => $colors[$label] ?? '#000000', // Default to black if color is not specified
                'backgroundColor' => 'rgba(0, 0, 0, 0.1)', // Light background color
                'borderWidth' => 2,
                'fill' => false,
            ];
            $datasets[] = $dataset;
        }

        return [
            'datasets' => $datasets,
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
