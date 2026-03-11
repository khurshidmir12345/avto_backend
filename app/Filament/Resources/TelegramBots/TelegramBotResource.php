<?php

namespace App\Filament\Resources\TelegramBots;

use App\Enums\BotType;
use App\Filament\Resources\TelegramBots\Pages\ManageTelegramBots;
use App\Models\TelegramBot;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

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
                    ->options(BotType::options())
                    ->required()
                    ->native(false),
                TextInput::make('token')
                    ->label('Token')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Tahrirlashda: yangi token kiritmasangiz, mavjud token saqlanadi.'),
                TextInput::make('channel_id')
                    ->label('Kanal ID (ixtiyoriy)')
                    ->placeholder('@kanal_username yoki -100xxxxxxxxxx')
                    ->helperText('Bot kanalda admin bo\'lishi kerak.'),
                TextInput::make('admin_chat_id')
                    ->label('Admin Chat ID')
                    ->placeholder('Masalan: 123456789')
                    ->numeric()
                    ->helperText('Support bot uchun: admin Telegram chat ID. Botga yozib /getUpdates orqali oling.')
                    ->visible(fn ($get) => $get('bot_type') === BotType::Support->value),
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
                    ->formatStateUsing(fn (mixed $state): string => $state instanceof BotType ? $state->label() : (BotType::tryFrom((string) $state)?->label() ?? 'Noma\'lum'))
                    ->color(fn (mixed $state): string => match ($state instanceof BotType ? $state->value : (string) $state) {
                        'elon_send_channel' => 'success',
                        'set_profile_bot' => 'info',
                        'notification' => 'warning',
                        'support' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('channel_id')
                    ->label('Kanal')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('admin_chat_id')
                    ->label('Admin Chat')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('token')
                    ->label('Token')
                    ->limit(20)
                    ->formatStateUsing(fn (?string $state): string => $state ? substr($state, 0, 15) . '...' : '—'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('setWebhook')
                    ->label('Webhook')
                    ->icon(Heroicon::OutlinedLink)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Webhook o\'rnatish')
                    ->modalDescription(fn (TelegramBot $record): string => "«{$record->bot_name}» uchun webhook o'rnatilsinmi?")
                    ->action(function (TelegramBot $record): void {
                        if (!$record->token) {
                            Notification::make()->title('Token topilmadi')->danger()->send();
                            return;
                        }

                        $webhookUrl = rtrim(config('app.url'), '/') . "/api/telegram/webhook/{$record->bot_type->value}";

                        $response = Http::post(
                            "https://api.telegram.org/bot{$record->token}/setWebhook",
                            ['url' => $webhookUrl]
                        );

                        if ($response->successful() && ($response->json('ok') === true)) {
                            Notification::make()
                                ->title('Webhook muvaffaqiyatli o\'rnatildi')
                                ->body($webhookUrl)
                                ->success()
                                ->send();
                        } else {
                            $desc = $response->json('description', 'Noma\'lum xato');
                            Notification::make()
                                ->title('Webhook o\'rnatishda xato')
                                ->body($desc)
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('removeWebhook')
                    ->label('Webhook o\'chirish')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Webhook o\'chirish')
                    ->modalDescription(fn (TelegramBot $record): string => "«{$record->bot_name}» webhookini o'chirishni xohlaysizmi?")
                    ->action(function (TelegramBot $record): void {
                        if (!$record->token) {
                            Notification::make()->title('Token topilmadi')->danger()->send();
                            return;
                        }

                        $response = Http::post(
                            "https://api.telegram.org/bot{$record->token}/deleteWebhook"
                        );

                        if ($response->successful() && ($response->json('ok') === true)) {
                            Notification::make()
                                ->title('Webhook o\'chirildi')
                                ->success()
                                ->send();
                        } else {
                            $desc = $response->json('description', 'Noma\'lum xato');
                            Notification::make()
                                ->title('Xato')
                                ->body($desc)
                                ->danger()
                                ->send();
                        }
                    }),
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
