<?php

namespace App\Console\Commands;

use App\Models\TelegramBot;
use App\Services\TelegramBotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramPollCommand extends Command
{
    protected $signature = 'telegram:poll {--bot=set_profile_bot}';

    protected $description = 'Lokal uchun: webhook o\'chirib, long polling ishlatadi';

    public function handle(TelegramBotService $service): int
    {
        $bot = TelegramBot::where('bot_type', $this->option('bot'))->first();

        if (!$bot) {
            $this->error('Bot topilmadi. Admin panelda set_profile_bot qo\'shing.');
            return self::FAILURE;
        }

        $this->deleteWebhook($bot->token);
        $this->info('Webhook o\'chirildi. Long polling boshlandi...');
        $this->info('To\'xtatish: Ctrl+C');
        $this->newLine();

        $offset = 0;

        while (true) {
            $response = Http::get("https://api.telegram.org/bot{$bot->token}/getUpdates", [
                'offset' => $offset,
                'timeout' => 30,
            ]);

            if (!$response->successful()) {
                sleep(1);
                continue;
            }

            $data = $response->json();
            $results = $data['result'] ?? [];

            foreach ($results as $update) {
                $offset = $update['update_id'] + 1;
                $service->handleSetProfileBotMessage($update);
            }

            usleep(100_000);
        }
    }

    private function deleteWebhook(string $token): void
    {
        Http::get("https://api.telegram.org/bot{$token}/deleteWebhook");
    }
}
