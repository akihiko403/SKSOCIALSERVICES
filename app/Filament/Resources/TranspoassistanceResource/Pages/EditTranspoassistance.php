<?php

namespace App\Filament\Resources\TranspoassistanceResource\Pages;

use App\Filament\Resources\TranspoassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranspoassistance extends EditRecord
{
    protected static string $resource = TranspoassistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
        
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}