<?php

namespace App\Filament\Resources\ElonPrices;

use App\Filament\Resources\ElonPrices\Pages\ManageElonPrices;
use App\Models\ElonPrice;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ElonPriceResource extends Resource
{
    protected static ?string $model = ElonPrice::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static \UnitEnum|string|null $navigationGroup = 'Sozlamalar';

    protected static ?string $modelLabel = 'E\'lon narxi';

    protected static ?string $pluralModelLabel = 'E\'lon narxlari';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Kalit')
                    ->required()
                    ->maxLength(255),
                TextInput::make('amount')
                    ->label('Summa (UZS)')
                    ->numeric()
                    ->required()
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('key')
                    ->label('Kalit')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Summa')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state) . ' UZS'),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageElonPrices::route('/'),
        ];
    }
}
