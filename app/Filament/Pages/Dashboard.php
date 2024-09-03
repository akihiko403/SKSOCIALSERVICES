<?php
 
namespace App\Filament\Pages;

use Filament\Panel;
use Filament\Forms\Form;
use App\Filament\Widgets\fuelwidget;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Concerns\HasFilters;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
 
class Dashboard extends \Filament\Pages\Dashboard
{
   use HasFiltersForm;

   public function filtersForm(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('')->schema([
                DatePicker::make('startDate')
                    ->default(today()), // Set default to today
                DatePicker::make('endDate')
                    ->default(today()), // Set default to today
            ])->columns(2)
        ]);
    }
    

   
}