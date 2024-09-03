<?php

namespace App\Filament\Resources\BloodassistanceResource\Pages;

use App\Filament\Resources\BloodassistanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodassistance extends EditRecord
{
    protected static string $resource = BloodassistanceResource::class;

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
