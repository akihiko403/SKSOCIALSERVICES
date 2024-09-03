<?php

namespace App\Filament\Resources;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\FuelLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Filament\Widgets\fuelwidget;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\FuelLogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use App\Filament\Resources\FuelLogResource\RelationManagers;

 

class FuelLogResource extends Resource
{
   // protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $navigationGroup = 'Fuel Monitoring';

    protected static ?string $model = FuelLog::class;
    protected static ?int $navigationSort = 4;

  //  protected static ?string $navigationIcon = 'heroicon-o-funnel';

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Date and Driver')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\Select::make('driver_id')
                            ->relationship('Driver', 'full_name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                    ]),

                Fieldset::make('Vehicle Information')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('Vehicle', 'plate_no')
                            ->label('Vehicle Plate Number')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                            Forms\Components\Select::make('products')
                            ->options([
                                'Deisel'  => 'Deisel',
                                'Premium'  => 'Premium',
                                'Unleaded'  => 'Unleaded',
                            ])
                            ->placeholder('Select Gasoline')
                            ->required(),
                    ]),

                Fieldset::make('Fuel Log Details')
                    ->schema([
                       
                        Forms\Components\TextInput::make('liters_requested')
                            ->required(),
                        Forms\Components\TextInput::make('actual_purchase_liters')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('price_per_liter')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('total_amount')
                            ->disabled(),
                        ])->columns(4),
                Fieldset::make('Vehicle Trip Details')
                            ->schema([
                        Forms\Components\TextInput::make('trip')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purpose')
                            ->required()
                            ->maxLength(255),
                    ]),

                Fieldset::make('Approval Information')
                    ->schema([
                        Forms\Components\Select::make('approved_by')
                            ->required()
                            ->label('Approve By')
                            ->relationship('User', 'name')
                            ->default(Auth::id())
                            ->disabled(),
                    ]),

                Fieldset::make('Additional Details')
                    ->schema([
                        Forms\Components\Textarea::make('remarks')
                        ->rows(8)
                        ->cols(10),
                            
                        Forms\Components\FileUpload::make('receipt')
                            ->image()
                            ->imageEditor()
                            ->maxSize(10 * 1024)
                            ->openable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->searchable()
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Driver.full_name')
                    ->searchable()
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Vehicle.unit')
                    ->sortable()
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight(),
                Tables\Columns\TextColumn::make('Vehicle.plate_no')
                    ->label('Plate Number')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->sortable(),
                Tables\Columns\TextColumn::make('products')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->sortable(),
                Tables\Columns\TextColumn::make('liters_requested')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_purchase_liters')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_liter')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trip')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('User.name')
                    ->label('Approved By')
                    ->size('lg')
                    ->extraAttributes(['class' => 'text-right'])
                    ->alignRight()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('receipt')
                     ->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                
                SelectFilter::make('Approved By')
                    ->relationship('User', 'name')
                    ->searchable()
                    ->preload(),
                    SelectFilter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        
                        DatePicker::make('created_until')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Store dates in the session
                        session([
                            'created_from' => $data['created_from'],
                            'created_until' => $data['created_until'],
                        ]);

                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('ReceiptFullscreen')
                ->label('ReceiptFullscreen')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('receipts.fullscreen', $record->id))
                ->openUrlInNewTab(), // Open in a new tab to preserve the full-screen view
             
            ])
            ->bulkActions([
                
                    ExportBulkAction::make(),
              
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
            'index' => Pages\ListFuelLogs::route('/'),
            'create' => Pages\CreateFuelLog::route('/create'),
            'view' => Pages\ViewFuelLog::route('/{record}'),
            'edit' => Pages\EditFuelLog::route('/{record}/edit'),
        ];
    }
    
  
}
