<?php

namespace App\Filament\Resources\UserTelegramChannels;

use App\Filament\Resources\UserTelegramChannels\Pages\ManageUserTelegramChannels;
use App\Models\UserTelegramChannel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UserTelegramChannelResource extends Resource
{
    protected static ?string $model = UserTelegramChannel::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Sozlamalar';

    protected static ?string $modelLabel = 'Foydalanuvchi kanali';

    protected static ?string $pluralModelLabel = 'Foydalanuvchi kanallari';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->label('Foydalanuvchi ID')
                    ->required()
                    ->numeric()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                TextInput::make('channel_name')
                    ->label('Kanal nomi')
                    ->maxLength(255),
                TextInput::make('channel_username')
                    ->label('Kanal username')
                    ->maxLength(255)
                    ->helperText('@ belgisisiz'),
                TextInput::make('chat_id')
                    ->label('Chat ID')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('-100xxxxxxxxxx'),
                TextInput::make('bot_token')
                    ->label('Bot token')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Tahrirlashda bo\'sh qoldirsangiz, mavjud token saqlanadi.'),
                Textarea::make('message_template')
                    ->label('Xabar shabloni')
                    ->rows(8)
                    ->maxLength(2000)
                    ->helperText('{marka}, {model}, {yil}, {narx}, {probeg}, {telefon}, {shahar}, {link}, {hashtag}, {footer}'),
                TextInput::make('footer_text')
                    ->label('Footer matni')
                    ->maxLength(500),
                Toggle::make('is_active')
                    ->label('Faol')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),
                TextColumn::make('user.name')
                    ->label('Foydalanuvchi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('channel_name')
                    ->label('Kanal nomi')
                    ->searchable()
                    ->placeholder('—')
                    ->weight('bold'),
                TextColumn::make('channel_username')
                    ->label('Username')
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => $state ? "@{$state}" : '—')
                    ->color('primary'),
                TextColumn::make('chat_id')
                    ->label('Chat ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('last_error_at')
                    ->label('Oxirgi xato')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->color('danger'),
                TextColumn::make('last_error_message')
                    ->label('Xato xabari')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Holati')
                    ->trueLabel('Faol')
                    ->falseLabel('Nofaol')
                    ->placeholder('Barchasi'),
                TernaryFilter::make('has_errors')
                    ->label('Xatolar')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('last_error_at'),
                        false: fn ($query) => $query->whereNull('last_error_at'),
                    )
                    ->trueLabel('Xatoli')
                    ->falseLabel('Xatosiz')
                    ->placeholder('Barchasi'),
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ManageUserTelegramChannels::route('/'),
        ];
    }
}
