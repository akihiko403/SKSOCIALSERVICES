<?php

namespace App\Filament\Resources\CrmcmedicalResource\Pages;

use App\Filament\Resources\CrmcmedicalResource;
use App\Filament\Resources\CrmcmedicalResource\Widgets\CrmcWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmcmedicals extends ListRecords
{
    protected static string $resource = CrmcmedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New CRMC Record'),
          
        ];
    }
    protected function getHeaderWidgets(): array{
        return [
         CrmcWidget::class,
        ];
    }
}
