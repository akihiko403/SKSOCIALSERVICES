<?php

namespace App\Filament\Resources\DriverResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use App\Filament\Resources\DriverResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
 
}
