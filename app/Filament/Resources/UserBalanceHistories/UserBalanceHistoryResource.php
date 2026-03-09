<?php

namespace App\Filament\Resources\UserBalanceHistories;

use App\Filament\Resources\UserBalanceHistories\Pages\ManageUserBalanceHistories;
use App\Models\UserBalanceHistory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserBalanceHistoryResource extends Resource
{
    protected static ?string $model = UserBalanceHistory::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Boshqaruv';

    protected static ?string $modelLabel = 'Balans tarixi';

    protected static ?string $pluralModelLabel = 'Balans tarixi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Foydalanuvchi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Turi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'credit' ? 'Kirim' : 'Chiqim')
                    ->color(fn (string $state): string => $state === 'credit' ? 'success' : 'danger'),
                TextColumn::make('amount')
                    ->label('Summa')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state) . ' UZS'),
                TextColumn::make('balance_after')
                    ->label('Balans keyin')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state) . ' UZS'),
                TextColumn::make('description')
                    ->label('Izoh')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Sana')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUserBalanceHistories::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
