<?php

namespace App\Filament\Resources\BloodassistanceResource\Widgets;

use App\Models\Bloodassistance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BloodAssistanceWidget extends BaseWidget
            { 
                protected static ?string $pollingInterval = '1s';


                protected function getStats(): array
            {
                // Retrieve the created_from and created_until dates from the session
                $createdFrom = Session::get('created_from');
                $createdUntil = Session::get('created_until');

                $dailyFuelLogCounts = DB::table('bloodassistance')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            // Format data for the fuel log chart
            $fuelLogChartData = $dailyFuelLogCounts->map(function ($item) {
                return $item->count;
            })->toArray();

                // Filter the FuelLog records based on the date range
                $query = Bloodassistance::query();

                if ($createdFrom) {
                    $query->whereDate('date', '>=', $createdFrom);
                }

                if ($createdUntil) {
                    $query->whereDate('date', '<=', $createdUntil);
                }

                $totalAmount = $query->sum('total_amount');
                
                $dailyFuelTotals = $query->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total_amount) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                $totalAmountChartData = $dailyFuelTotals->map(function ($item) {
                    return $item->total;
                })->toArray();

                return [
                    Stat::make('Set the Starting Date and End date to calculate', number_format($totalAmount, 2))
                        ->description('Total amount of Blood Assistance')
                        ->chart($totalAmountChartData)
                        ->descriptionIcon('heroicon-o-currency-dollar')
                        ->color('success'),

                        Stat::make('Blood Assistance Records',Bloodassistance::query()
                        ->when($createdFrom, fn($query) => $query->whereDate('created_at', '>=',$createdFrom))
                        ->when($createdUntil, fn($query) => $query->whereDate('created_at', '<=', $createdUntil))
                        ->count())
                        ->chart($fuelLogChartData)
                        ->description('All Blood Assistance Records')
                        ->descriptionIcon('heroicon-m-plus')
                        
                        
                        ->color('success'),
                ];
            }
}
