<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\QueryException;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleResource\RelationManagers;

class VehicleResource extends Resource
{
    protected static ?string $navigationGroup = 'Fuel Monitoring';
    protected ?string $maxContentWidth = 'xL';
 //   protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $model = Vehicle::class;
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

//    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Fieldset::make('Vehicle Details')
                ->schema([
                    Forms\Components\TextInput::make('unit')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('plate_no')
                        ->required()
                        ->unique()
                        ->maxLength(255),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit')
                    
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate_no')
                    
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    
                    ->size('lg')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    
                    ->size('lg')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),      
                DeleteAction::make()
                ->action(function ($record) {
                    try {
                        $record->delete();
                        Notification::make()
                            ->title('Record deleted successfully.')
                            ->success()
                            ->send();
                    } catch (QueryException $e) {
                        // Check if the exception is due to a foreign key constraint
                        if ($e->getCode() === '23000') {
                            Notification::make()
                                ->title('Cannot delete this item because it is referenced by another record.')
                                ->danger()
                                ->send();
                        } else {
                            // Handle other exceptions if needed
                            Notification::make()
                                ->title('An error occurred while deleting the record.')
                                ->danger()
                                ->send();
                        }
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
