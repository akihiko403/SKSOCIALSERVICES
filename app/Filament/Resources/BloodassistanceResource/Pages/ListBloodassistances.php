<?php

namespace App\Filament\Resources\BloodassistanceResource\Pages;

use App\Filament\Resources\BloodassistanceResource;
use App\Filament\Resources\BloodassistanceResource\Widgets\BloodAssistanceWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodassistances extends ListRecords
{
    protected static string $resource = BloodassistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Blood Assistance'),
        ];
    }
    protected function getHeaderWidgets(): array{
        return [
         BloodAssistanceWidget::class,
        ];
    }
}
