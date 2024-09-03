<?php

namespace App\Filament\Resources\SpmcmedicalResource\Pages;

use App\Filament\Resources\SpmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpmcmedical extends CreateRecord
{
    protected static string $resource = SpmcmedicalResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
