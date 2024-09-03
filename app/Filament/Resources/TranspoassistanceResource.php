<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AddressCityMun;
use Filament\Resources\Resource;
use App\Models\Transpoassistance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\TranspoassistanceResource\Pages;
use App\Filament\Resources\TranspoassistanceResource\RelationManagers;

class TranspoassistanceResource extends Resource
{
    protected static ?string $model = Transpoassistance::class;
    protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $navigationGroup = 'Social Service';
    protected static ?string $navigationLabel = 'Transportation Assistance Records ';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Fieldset::make('Referral Details')
                    ->schema([
                        Forms\Components\Select::make('status')
                          ->options([
                            'Verified' => 'Verified',
                            'Unverified' => 'Unverified',
                        ]),
                Forms\Components\DatePicker::make('referral_date')
                         ->required(),
                Forms\Components\TextInput::make('referral')
                        ->label('Reffered By:')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),

            Forms\Components\Fieldset::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('lastname')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('firstname')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('middlename')
                        ->maxLength(100)
                        ->default(null),
                    Forms\Components\Select::make('ext')
                        ->label('EXT')
                        ->options([
                            '' => 'None',
                            'Jr.' => 'Jr. (Junior)',
                            'Sr.' => 'Sr. (Senior)',
                            'II' => 'II (Second)',
                            'III' => 'III (Third)',
                            'IV' => 'IV (Fourth)',
                            'V' => 'V (Fifth)',
                            'VI' => 'VI (Sixth)',
                            'VII' => 'VII (Seventh)',
                            'VIII' => 'VIII (Eighth)',
                            'IX' => 'IX (Ninth)',
                            'X' => 'X (Tenth)',
                        ])
                        ->default('NONE'),
                    Forms\Components\TextInput::make('age')
                        ->required()
                        ->numeric(),
                    Forms\Components\Select::make('municipality')
                        ->label('Municipality')
                        ->searchable()
                        ->options(function () {
                            return AddressCityMun::all()->pluck('municipality_name', 'municipality_name')->toArray();
                        })
                        ->default(null)
                        ->required(),
                ]),
        
           
        
            Forms\Components\Fieldset::make('Transportation Information')
                ->schema([
                    Forms\Components\TextInput::make('pick_up_point')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('drop_point')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('unit')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('name_of_driver')
                        ->required()
                        ->maxLength(100),
                ]),
        
            Forms\Components\Fieldset::make('Medical Details')
                ->schema([
                    Forms\Components\Textarea::make('diagnosis_cause_of_death')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('remarks')
                        ->columnSpanFull(),
                ]),
                Forms\Components\Fieldset::make('Encoding Information')
                ->schema([
                    Forms\Components\Select::make('encodedby')
                        ->required()
                        ->label('Encoded By')
                        ->relationship('User', 'name')
                        ->default(Auth::id())
                        ->disabled(),
                ]),            
            ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('status')
                ->color(function (string $state): string {
                    return match ($state) {
                        'Verified' => 'success',
                        'Unverified' => 'danger',
                    };
                })
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('referral_date')
                ->date()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('referral')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('lastname')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('firstname')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('middlename')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('ext')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('municipality')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('age')
                ->numeric()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('pick_up_point')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('drop_point')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('unit')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('name_of_driver')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->size('lg'),
        ])
        
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTranspoassistances::route('/'),
            'create' => Pages\CreateTranspoassistance::route('/create'),
            'view' => Pages\ViewTranspoassistance::route('/{record}'),
            'edit' => Pages\EditTranspoassistance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
