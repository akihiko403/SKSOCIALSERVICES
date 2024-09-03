<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Driver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\QueryException;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DriverResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DriverResource\RelationManagers;

class DriverResource extends Resource
{
    protected static ?string $navigationGroup = 'Fuel Monitoring';
    protected static ?string $model = Driver::class;
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
//    protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
 //   protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Fieldset::make('Contact Information')
                ->schema([
                    Forms\Components\TextInput::make('full_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contact')
                        ->tel()
                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                        ->required()
                        ->numeric()
                        ->minLength(11)
                        ->maxLength(11),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                 //   ->alignRight()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                 //   ->alignRight()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                 //   ->alignRight()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                 //   ->alignRight()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
               //     ->alignRight()
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'view' => Pages\ViewDriver::route('/{record}'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
