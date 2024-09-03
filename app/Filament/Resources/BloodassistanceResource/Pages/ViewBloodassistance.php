<?php

namespace App\Filament\Resources\BloodassistanceResource\Pages;

use App\Filament\Resources\BloodassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBloodassistance extends ViewRecord
{
    protected static string $resource = BloodassistanceResource::class;
       protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
