<?php

namespace App\Services;

use App\Models\TelegramBot;
use App\Models\TelegramLinkToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        $bot = TelegramBot::where('bot_type', \App\Enums\BotType::SetProfileBot->value)->first();
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

    /**
     * Support bot: user xabarini adminga forward qilish, admin replyni userga yuborish.
     */
    public function handleSupportBotMessage(TelegramBot $bot, array $payload): void
    {
        if (empty($payload['message'])) {
            return;
        }

        $message = $payload['message'];
        $chatId = $message['chat']['id'] ?? null;
        $adminChatId = $bot->admin_chat_id;

        if (!$chatId || !$adminChatId) {
            return;
        }

        if ((string) $chatId === (string) $adminChatId) {
            $this->handleAdminReply($bot, $message);
            return;
        }

        $this->forwardToAdmin($bot, $chatId, $message);
    }

    private function forwardToAdmin(TelegramBot $bot, int|string $userChatId, array $message): void
    {
        $messageId = $message['message_id'] ?? null;
        if (!$messageId) {
            return;
        }

        Http::post("https://api.telegram.org/bot{$bot->token}/forwardMessage", [
            'chat_id' => $bot->admin_chat_id,
            'from_chat_id' => $userChatId,
            'message_id' => $messageId,
        ]);
    }

    private function handleAdminReply(TelegramBot $bot, array $message): void
    {
        $replyTo = $message['reply_to_message'] ?? null;
        if (!$replyTo) {
            return;
        }

        $originalChatId = $replyTo['forward_from']['id']
            ?? $replyTo['forward_sender_name'] // can't reply if hidden
            ?? null;

        if (!$originalChatId || !is_numeric($originalChatId)) {
            $forwardOrigin = $replyTo['forward_origin'] ?? null;
            if ($forwardOrigin && ($forwardOrigin['type'] ?? '') === 'user') {
                $originalChatId = $forwardOrigin['sender_user']['id'] ?? null;
            }
        }

        if (!$originalChatId) {
            $this->sendMessage(
                $bot->token,
                $bot->admin_chat_id,
                "Foydalanuvchi aniqlanmadi. Forwarded xabarga reply qiling."
            );
            return;
        }

        $text = $message['text'] ?? null;
        if ($text) {
            $this->sendMessage($bot->token, $originalChatId, $text);
        }
    }

    public function sendMessage(string $botToken, int|string $chatId, string $text, ?string $url = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($url) {
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [['text' => '🔗 Hisobni ulash', 'url' => $url]],
                ],
            ]);
        }

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $params);
    }
}
