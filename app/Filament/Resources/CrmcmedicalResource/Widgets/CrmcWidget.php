<?php

namespace App\Filament\Resources\CrmcmedicalResource\Widgets;

use App\Models\Crmcmedical;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class CrmcWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';


    protected function getStats(): array
{
    // Retrieve the created_from and created_until dates from the session
    $createdFrom = Session::get('created_from');
    $createdUntil = Session::get('created_until');

    $dailyFuelLogCounts = DB::table('crmcmedical')
    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
    ->groupBy(DB::raw('DATE(created_at)'))
    ->orderBy('date')
    ->get();

// Format data for the fuel log chart
$fuelLogChartData = $dailyFuelLogCounts->map(function ($item) {
    return $item->count;
})->toArray();

    // Filter the FuelLog records based on the date range
    $query = Crmcmedical::query();

    if ($createdFrom) {
        $query->whereDate('date', '>=', $createdFrom);
    }

    if ($createdUntil) {
        $query->whereDate('date', '<=', $createdUntil);
    }

    $totalAmount = $query->sum('amount');
    
    $dailyFuelTotals = $query->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $totalAmountChartData = $dailyFuelTotals->map(function ($item) {
        return $item->total;
    })->toArray();

    return [
        Stat::make('Total Amounts', number_format($totalAmount, 2))
            ->description('Set the Starting and End date to calculate')
           
            ->descriptionIcon('heroicon-o-currency-dollar')
            ->color('success'),

            Stat::make('CRMC Assistance Records',Crmcmedical::query()
            ->when($createdFrom, fn($query) => $query->whereDate('created_at', '>=',$createdFrom))
            ->when($createdUntil, fn($query) => $query->whereDate('created_at', '<=', $createdUntil))
            ->count())
            
            ->description('All CRMC Assistance Records')
            ->descriptionIcon('heroicon-m-plus')
            
            
            ->color('success'),
    ];
}
}
