<?php

namespace App\Filament\Resources\TranspoassistanceResource\Pages;

use App\Filament\Resources\TranspoassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranspoassistances extends ListRecords
{
    protected static string $resource = TranspoassistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Transportation Assistance'),
        ];
    }
}
