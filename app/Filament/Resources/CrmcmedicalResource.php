<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Province;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Crmcmedical;
use App\Models\Municipality;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CrmcmedicalResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\CrmcmedicalResource\RelationManagers;

class CrmcmedicalResource extends Resource
{
    protected static ?string $navigationGroup = 'Social Service';
    protected static ?string $navigationLabel = 'CRMC Medical Assistance ';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Crmcmedical::class;
    protected static ?string $activeNavigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Fieldset::make('Basic Information')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'Verified' => 'Verified',
                            'Unverified' => 'Unverified',
                        ])
                        ->required(),
                    Forms\Components\DatePicker::make('date')
                        ->required(),
                    Forms\Components\TextInput::make('referral')
                        ->maxLength(255)
                        ->default(null),
                   
                ])->columns(3),
            
            Forms\Components\Fieldset::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('lastname')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('firstname')
                    ->label('First Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middlename')
                    ->label('Middle Name')
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
            Fieldset::make('Medical information')
                ->schema([ 
                Forms\Components\TextArea::make('diagnosis')
                    ->maxLength(255)
                    ->default(null),
                ])->columns(1),


            Forms\Components\Fieldset::make('Location Details')
                ->schema([
                    Select::make('province')
                        ->options(function () {
                            return Province::all()->pluck('province_name', 'province_name')->toArray();
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $province = Province::where('province_name', $state)->first();
                            $municipalities = $province
                                ? Municipality::where('province_id', $province->province_id)->pluck('municipality_name', 'municipality_name')->toArray()
                                : [];
                            $set('municipalities', $municipalities);
                            $set('municipality', null);
                        })
                        ->afterStateHydrated(function (callable $set, callable $get, $state) {
                            $province = Province::where('province_name', $state)->first();
                            if ($province) {
                                $municipalities = Municipality::where('province_id', $province->province_id)->pluck('municipality_name', 'municipality_name')->toArray();
                                $set('municipalities', $municipalities);
                            }
                        }),
                    Select::make('municipality')
                        ->options(fn($get) => $get('municipalities') ?? [])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $set('municipality', $state);
                        })
                        ->afterStateHydrated(function (callable $set, $state) {
                            $set('municipality', $state);
                        }),
                ]),

            Forms\Components\Fieldset::make('Status Information')
                ->schema([
                    Forms\Components\Select::make('patient_status')
                        ->required()
                        ->options([
                            'admitted' => 'Admitted',
                            'discharged' => 'Discharged',
                        ])
                        ->placeholder('Select patient status'),
                    Forms\Components\Select::make('transaction_status')
                        ->required()
                        ->options([
                            'admitted' => 'Admitted',
                            'discharged' => 'Discharged',
                        ])
                        ->placeholder('Select transaction status'),
                ]),

            Forms\Components\Fieldset::make('Financial Details')
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric(),
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
        
            Tables\Columns\TextColumn::make('diagnosis')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('age')
                ->numeric()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('birthdate')
                ->date()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('municipality')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('province')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('patient_status')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('transaction_status')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('amount')
                ->numeric()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('date')
                ->date()
                ->sortable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('referral')
                ->label('Referred By')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('User.name')
                ->label('Encoded By')
                ->searchable()
                ->size('lg'),
        
            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListCrmcmedicals::route('/'),
            'create' => Pages\CreateCrmcmedical::route('/create'),
            'view' => Pages\ViewCrmcmedical::route('/{record}'),
            'edit' => Pages\EditCrmcmedical::route('/{record}/edit'),
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
