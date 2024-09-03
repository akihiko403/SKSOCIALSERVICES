<?php

namespace App\Filament\Resources\BloodassistanceResource\Pages;

use App\Filament\Resources\BloodassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBloodassistance extends CreateRecord
{
    protected static string $resource = BloodassistanceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
