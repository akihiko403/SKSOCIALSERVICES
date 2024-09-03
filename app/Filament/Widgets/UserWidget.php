<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\FuelLog;
use App\Models\CrmcMedical;
use App\Models\SpmcMedical;
use App\Models\BloodAssistance;
use App\Models\TranspoAssistance;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UserWidget extends BaseWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Retrieve start and end dates from filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Convert filters to Carbon instances
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // Helper function to get chart data
        $getChartData = function ($modelClass, $dateColumn, $dateFormat, $countColumn) use ($startDate, $endDate) {
            $query = DB::table((new $modelClass)->getTable())
                ->select(DB::raw("DATE_FORMAT($dateColumn, '%Y-%m') as $dateFormat"), DB::raw("COUNT(*) as $countColumn"))
                ->groupBy(DB::raw("DATE_FORMAT($dateColumn, '%Y-%m')"))
                ->orderBy($dateFormat);

            if ($startDate) {
                $query->whereDate($dateColumn, '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate($dateColumn, '<=', $endDate);
            }

            $data = $query->get();
            return $data->pluck($countColumn)->toArray();
        };

        // Calculate chart data for each model
        $fuelLogChartData = $getChartData(FuelLog::class, 'created_at', 'date', 'count');
        $bloodAssistanceChartData = $getChartData(BloodAssistance::class, 'created_at', 'date', 'count');
        $crmcMedicalChartData = $getChartData(CrmcMedical::class, 'created_at', 'date', 'count');
        $spmcMedicalChartData = $getChartData(SpmcMedical::class, 'created_at', 'date', 'count');
        $transpoAssistanceChartData = $getChartData(TranspoAssistance::class, 'created_at', 'date', 'count');

        return [
            Stat::make('Fuel Logs', FuelLog::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->count())
                ->chart($fuelLogChartData)
                ->description('All Fuel Logs')
                ->descriptionIcon('heroicon-m-funnel')
                ->color('success'),

            Stat::make('Blood Assistance', BloodAssistance::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->count())
                ->chart($bloodAssistanceChartData)
                ->description('All Blood Assistance Records')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('CRMC Medical', CrmcMedical::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->count())
                ->chart($crmcMedicalChartData)
                ->description('All CRMC Medical Records')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('SPMC Medical', SpmcMedical::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->count())
                ->chart($spmcMedicalChartData)
                ->description('All SPMC Medical Records')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Transpo Assistance', TranspoAssistance::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->count())
                ->chart($transpoAssistanceChartData)
                ->description('All Transpo Assistance Records')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
        ];
    }
}
