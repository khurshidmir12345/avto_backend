<?php

namespace App\Filament\Resources\BlockedUsers;

use App\Filament\Resources\BlockedUsers\Pages\ManageBlockedUsers;
use App\Models\BlockedUser;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlockedUserResource extends Resource
{
    protected static ?string $model = BlockedUser::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static \UnitEnum|string|null $navigationGroup = 'Moderatsiya';

    protected static ?string $modelLabel = 'Bloklangan foydalanuvchi';

    protected static ?string $pluralModelLabel = 'Bloklangan foydalanuvchilar';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Bloklagan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.phone')
                    ->label('Telefon'),
                TextColumn::make('blockedUser.name')
                    ->label('Bloklangan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('blockedUser.phone')
                    ->label('Telefon'),
                TextColumn::make('created_at')
                    ->label('Sana')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                DeleteAction::make()
                    ->label('Blokni olib tashlash'),
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
            'index' => ManageBlockedUsers::route('/'),
        ];
    }
}
