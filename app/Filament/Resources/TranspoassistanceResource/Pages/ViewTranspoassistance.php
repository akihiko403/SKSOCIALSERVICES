<?php

namespace App\Filament\Resources\TranspoassistanceResource\Pages;

use App\Filament\Resources\TranspoassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTranspoassistance extends ViewRecord
{
    protected static string $resource = TranspoassistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
