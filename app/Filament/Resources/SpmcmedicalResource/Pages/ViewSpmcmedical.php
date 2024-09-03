<?php

namespace App\Filament\Resources\SpmcmedicalResource\Pages;

use App\Filament\Resources\SpmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpmcmedical extends ViewRecord
{
    protected static string $resource = SpmcmedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
