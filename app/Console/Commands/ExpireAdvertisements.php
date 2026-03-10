<?php

namespace App\Console\Commands;

use App\Services\AdvertisementService;
use Illuminate\Console\Command;

class ExpireAdvertisements extends Command
{
    protected $signature = 'ads:expire';

    protected $description = 'Muddati tugagan reklamalarni expired holatiga o\'tkazish';

    public function handle(AdvertisementService $service): int
    {
        $count = $service->expireOldAds();

        $this->info("{$count} ta reklama expired holatiga o'tkazildi.");

        return self::SUCCESS;
    }
}
