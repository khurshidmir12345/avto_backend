<?php

namespace App\Jobs;

use App\Models\MoshinaElon;
use App\Services\UserTelegramChannelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendElonToUserChannelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public array $backoff = [30, 60];
    public int $timeout = 180;

    public function __construct(
        private readonly int $elonId,
    ) {}

    public function handle(UserTelegramChannelService $service): void
    {
        $elon = MoshinaElon::with(['images', 'user.activeTelegramChannels'])->find($this->elonId);

        if (!$elon) {
            Log::warning("SendElonToUserChannelsJob: Elon #{$this->elonId} topilmadi");
            return;
        }

        if ($elon->user->activeTelegramChannels->isEmpty()) {
            return;
        }

        if ($elon->images->isEmpty()) {
            Log::info("SendElonToUserChannelsJob: Elon #{$this->elonId} da rasm yo'q");
            return;
        }

        $service->sendElonToUserChannels($elon);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendElonToUserChannelsJob: Elon #{$this->elonId} xato", [
            'error' => $exception->getMessage(),
        ]);
    }
}
