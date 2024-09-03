<?php

namespace App\Filament\Resources\CrmcmedicalResource\Pages;

use App\Filament\Resources\CrmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCrmcmedical extends CreateRecord
{

    protected static string $resource = CrmcmedicalResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
