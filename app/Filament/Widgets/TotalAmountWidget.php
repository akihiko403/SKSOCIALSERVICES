<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\FuelLog;
use App\Models\CrmcMedical;
use App\Models\SpmcMedical;
use App\Models\BloodAssistance;
use App\Models\TranspoAssistance;
use Illuminate\Support\Facades\Gate;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalAmountWidget extends BaseWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;
    protected static ?int $sort = 2; // Adjust sorting if necessary
    
    protected function getStats(): array
    {
        // Retrieve start and end dates from filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Convert filters to Carbon instances
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // Helper function to get total amounts
        $getTotalAmount = function ($modelClass, $amountColumn) use ($startDate, $endDate) {
            return $modelClass::query()
                ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
                ->sum($amountColumn);
        };

        return [
            Stat::make('Total Fuel Amount', number_format($getTotalAmount(FuelLog::class, 'total_amount'), 2))
                ->description('Total amount for all Fuel Logs')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Blood Assistance Amount', number_format($getTotalAmount(BloodAssistance::class, 'total_amount'), 2))
                ->description('Total amount for all Blood Assistance Records')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total CRMC Medical Amount', number_format($getTotalAmount(CrmcMedical::class, 'amount'), 2))
                ->description('Total amount for all CRMC Medical Records')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total SPMC Medical Amount', number_format($getTotalAmount(SpmcMedical::class, 'amount'), 2))
                ->description('Total amount for all SPMC Medical Records')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

           
        ];
    }
}
