<?php

namespace App\Filament\Resources\SpmcmedicalResource\Pages;

use App\Filament\Resources\SpmcmedicalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpmcmedical extends EditRecord
{
    protected static string $resource = SpmcmedicalResource::class;

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
