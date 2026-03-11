<?php

namespace App\Jobs;

use App\Models\MoshinaElon;
use App\Services\TelegramChannelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendElonToTelegramChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var array<int> Retry backoff (soniyalarda) */
    public array $backoff = [30, 60, 120];

    public int $timeout = 120;

    public function __construct(
        private readonly int $elonId,
    ) {}

    public function handle(TelegramChannelService $channelService): void
    {
        $elon = MoshinaElon::with(['images', 'user'])->find($this->elonId);

        if (! $elon) {
            Log::warning("SendElonToTelegramChannelJob: Elon #{$this->elonId} topilmadi");
            return;
        }

        if ($elon->images->isEmpty()) {
            Log::info("SendElonToTelegramChannelJob: Elon #{$this->elonId} da rasm yo'q");
            return;
        }

        $alreadySent = $elon->telegramSentMessages()->exists();
        if ($alreadySent) {
            Log::info("SendElonToTelegramChannelJob: Elon #{$this->elonId} allaqachon yuborilgan");
            return;
        }

        $result = $channelService->sendElon($elon);

        if ($result) {
            Log::info("SendElonToTelegramChannelJob: Elon #{$this->elonId} kanalga yuborildi", [
                'message_id' => $result->message_id,
                'channel_id' => $result->channel_id,
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendElonToTelegramChannelJob: Elon #{$this->elonId} yuborishda xatolik", [
            'error' => $exception->getMessage(),
        ]);
    }
}
