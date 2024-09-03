<?php

namespace App\Filament\Resources\CrmcmedicalResource\Pages;

use App\Filament\Resources\CrmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrmcmedical extends EditRecord
{
    protected static string $resource = CrmcmedicalResource::class;

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
