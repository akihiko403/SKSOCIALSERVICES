<?php

namespace App\Filament\Resources\CrmcmedicalResource\Pages;

use App\Filament\Resources\CrmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCrmcmedical extends ViewRecord
{
    protected static string $resource = CrmcmedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
