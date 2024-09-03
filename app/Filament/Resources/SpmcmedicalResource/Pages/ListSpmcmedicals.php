<?php

namespace App\Filament\Resources\SpmcmedicalResource\Pages;

use App\Filament\Resources\SpmcmedicalResource;

use App\Filament\Resources\SpmcmedicalResource\Widgets\SpmcWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpmcmedicals extends ListRecords
{
    protected static string $resource = SpmcmedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New SPMC Record'),
        ];
    }
    protected function getHeaderWidgets(): array{
        return [
        SpmcWidget::class,
        ];
    }
}
