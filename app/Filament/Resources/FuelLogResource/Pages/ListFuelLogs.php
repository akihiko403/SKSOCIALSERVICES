<?php

namespace App\Filament\Resources\FuelLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FuelLogResource;
use App\Filament\Resources\FuelLogResource\Widgets\fuelwidget;



class ListFuelLogs extends ListRecords
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array{
        return [
       fuelwidget::class,
        ];
    }
}
