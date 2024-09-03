<?php

namespace App\Filament\Resources\TranspoassistanceResource\Pages;

use App\Filament\Resources\TranspoassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTranspoassistance extends CreateRecord
{
    protected static string $resource = TranspoassistanceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
