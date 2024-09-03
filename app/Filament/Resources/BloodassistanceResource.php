<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AddressCityMun;
use App\Models\Bloodassistance;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BloodassistanceResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\BloodassistanceResource\RelationManagers;

class BloodassistanceResource extends Resource
{
    protected static ?string $model = Bloodassistance::class;
    protected static ?string $navigationGroup = 'Social Service';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Blood Assistance Records ';
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Initial Details')
                    ->schema([
                        Forms\Components\Select::make('status')
                        ->options([
                            'Verified' => 'Verified',
                            'Unverified' => 'Unverified',
                        ])
                        ->required(),
                        Forms\Components\TextInput::make('agency')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\TextInput::make('control_no')
                            ->unique()
                            ->required()
                            ->maxLength(255),
                       
                        Forms\Components\TextInput::make('referral')
                        ->label('Reffered By:')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\Fieldset::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('lastname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('firstname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('middlename')
                            ->maxLength(255)
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
                            ->default('NONE')
                            ->default(null),
                        Forms\Components\DatePicker::make('birthdate')
                            ->required(),
                        Forms\Components\TextInput::make('age')
                            ->disabled(),
                    ]),

                Forms\Components\Fieldset::make('Medical Details')
                    ->schema([
                        Forms\Components\TextArea::make('diagnosis')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hospital')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('blood_type')
                            ->options([
                                'A+'  => 'A+',
                                'A-'  => 'A-',
                                'B+'  => 'B+',
                                'B-'  => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+'  => 'O+',
                                'O-'  => 'O-',
                            ])
                            ->placeholder('Select Blood Type')
                            ->required(),
                        Forms\Components\TextInput::make('qty')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('unit_price')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('total_amount')
                            ->disabled(),
                    ]),

                Forms\Components\Fieldset::make('Administrative Details')
                    ->schema([
                        Forms\Components\Select::make('municipality')
                            ->label('Municipality')
                            ->searchable()
                            ->options(function () {
                                return AddressCityMun::all()->pluck('municipality_name', 'municipality_name')->toArray();
                            })
                            ->default(null)
                            ->required(),
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
                Tables\Columns\TextColumn::make('control_no')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Verified' => 'success',
                            'Unverified' => 'danger',
                        };
                    })
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agency')
                    ->size('lg')
                    ->searchable(),
                
               
               
                Tables\Columns\TextColumn::make('lastname')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middlename')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ext')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->size('lg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->size('lg')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('municipality')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diagnosis')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hospital')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('blood_type')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->size('lg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->size('lg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->size('lg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('User.name')
                    ->size('lg')
                    ->label('Encoded By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referral')
                    ->label('Referred by')
                    ->size('lg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->size('lg')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->size('lg')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListBloodassistances::route('/'),
            'create' => Pages\CreateBloodassistance::route('/create'),
            'view' => Pages\ViewBloodassistance::route('/{record}'),
            'edit' => Pages\EditBloodassistance::route('/{record}/edit'),
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
