<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\CrmcMedical;
use App\Models\SpmcMedical;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DailyAmountsWidget1 extends ChartWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Daily Amounts for SPMC Medical and CRMC Medical';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Retrieve start and end dates from filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Prepare queries for each model
        $models = [
            'SPMC Medical' => SpmcMedical::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date'),
            'CRMC Medical' => CrmcMedical::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date'),
        ];

        // Apply date filters to each query
        foreach ($models as $key => $query) {
            if ($startDate) {
                $models[$key]->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            }

            if ($endDate) {
                $models[$key]->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
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
            'SPMC Medical' => '#9C27B0', // Purple
            'CRMC Medical' => '#2196F3', // Blue
        ];

        $datasets = [];
        foreach ($data as $label => $modelData) {
            $dataset = [
                'label' => $label,
                'data' => array_map(fn($date) => $modelData->where('date', $date)->sum('amount'), $chartLabels),
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
