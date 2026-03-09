<?php

namespace App\Services;

use App\Models\TelegramBot;
use App\Models\TelegramLinkToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramBotService
{
    public function handleSetProfileBotMessage(array $payload): void
    {
        if (empty($payload['message'])) {
            return;
        }

        $message = $payload['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = trim($message['text'] ?? '');

        if (!$chatId) {
            return;
        }

        $from = $message['from'] ?? [];
        $telegramUserId = $from['id'] ?? null;
        $username = $from['username'] ?? null;
        $firstName = $from['first_name'] ?? null;
        $lastName = $from['last_name'] ?? null;

        if (!$telegramUserId) {
            return;
        }

        $bot = TelegramBot::where('bot_type', 'set_profile_bot')->first();
        if (!$bot) {
            return;
        }

        $token = Str::random(48);
        $webUrl = rtrim(config('telegram.link.web_url', config('app.url')), '/');
        $linkUrl = "{$webUrl}/telegram-link?token={$token}";

        TelegramLinkToken::where('telegram_user_id', $telegramUserId)->delete();

        TelegramLinkToken::create([
            'token' => $token,
            'telegram_user_id' => $telegramUserId,
            'telegram_username' => $username,
            'telegram_first_name' => $firstName,
            'telegram_last_name' => $lastName,
            'expires_at' => now()->addMinutes(10),
        ]);

        $replyText = "Telegram hisobingizni Avto Vodiy ilovasiga ulash uchun pastdagi tugmani bosing.";
        $this->sendMessage($bot->token, $chatId, $replyText, $linkUrl);
    }

    public function sendMessage(string $botToken, int|string $chatId, string $text, ?string $url = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($url) {
            // Web App — link ko'rinmasdan, tugma orqali ochiladi (ishonchliroq)
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [['text' => 'Ulash 🔗', 'web_app' => ['url' => $url]]],
                ],
            ]);
        }

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $params);
    }
}
