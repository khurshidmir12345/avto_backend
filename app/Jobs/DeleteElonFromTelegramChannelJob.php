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

class DeleteElonFromTelegramChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(
        private readonly int $elonId,
    ) {}

    public function handle(TelegramChannelService $channelService): void
    {
        $elon = MoshinaElon::find($this->elonId);

        if (! $elon) {
            Log::warning("DeleteElonFromTelegramChannelJob: Elon #{$this->elonId} topilmadi");
            return;
        }

        $channelService->deleteElonMessages($elon);

        Log::info("DeleteElonFromTelegramChannelJob: Elon #{$this->elonId} kanaldan o'chirildi");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("DeleteElonFromTelegramChannelJob: Elon #{$this->elonId} o'chirishda xatolik", [
            'error' => $exception->getMessage(),
        ]);
    }
}
