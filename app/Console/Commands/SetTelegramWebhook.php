<?php

namespace App\Console\Commands;

use App\Models\TelegramBot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {--bot=set_profile_bot : Bot turi}';

    protected $description = 'Telegram set_profile_bot uchun webhook o\'rnatadi';

    public function handle(): int
    {
        $botType = $this->option('bot');
        $bot = TelegramBot::where('bot_type', $botType)->first();

        if (!$bot) {
            $this->error("Bot topilmadi: {$botType}");
            return self::FAILURE;
        }

        $webhookUrl = rtrim(config('app.url'), '/') . '/api/telegram/webhook/' . $botType;

        $response = Http::post("https://api.telegram.org/bot{$bot->token}/setWebhook", [
            'url' => $webhookUrl,
        ]);

        if (!$response->successful()) {
            $this->error('Webhook o\'rnatishda xatolik: ' . $response->body());
            return self::FAILURE;
        }

        $data = $response->json();
        if (!($data['ok'] ?? false)) {
            $this->error('Telegram xatosi: ' . ($data['description'] ?? 'Noma\'lum'));
            return self::FAILURE;
        }

        $this->info("Webhook muvaffaqiyatli o'rnatildi: {$webhookUrl}");
        return self::SUCCESS;
    }
}
