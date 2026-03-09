<?php

namespace App\Filament\Resources\TelegramBots;

use App\Filament\Resources\TelegramBots\Pages\ManageTelegramBots;
use App\Models\TelegramBot;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TelegramBotResource extends Resource
{
    protected static ?string $model = TelegramBot::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static \UnitEnum|string|null $navigationGroup = 'Sozlamalar';

    protected static ?string $modelLabel = 'Telegram bot';

    protected static ?string $pluralModelLabel = 'Telegram botlar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bot_name')
                    ->label('Bot nomi')
                    ->required()
                    ->maxLength(255),
                Select::make('bot_type')
                    ->label('Bot turi')
                    ->options([
                        'set_profile_bot' => 'Profil ulash (Telegram)',
                        'notification' => 'Bildirishnoma',
                        'support' => 'Qo\'llab-quvvatlash',
                        'announcement' => 'E\'lon',
                        'other' => 'Boshqa',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('token')
                    ->label('Token')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Tahrirlashda: yangi token kiritmasangiz, mavjud token saqlanadi.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('bot_name')
                    ->label('Bot nomi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bot_type')
                    ->label('Bot turi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'set_profile_bot' => 'Profil ulash',
                        'notification' => 'Bildirishnoma',
                        'support' => 'Qo\'llab-quvvatlash',
                        'announcement' => 'E\'lon',
                        default => 'Boshqa',
                    })
                    ->sortable(),
                TextColumn::make('token')
                    ->label('Token')
                    ->limit(20)
                    ->formatStateUsing(fn (?string $state): string => $state ? substr($state, 0, 15) . '...' : '-'),
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
            'index' => ManageTelegramBots::route('/'),
        ];
    }
}
