<?php

namespace App\Services;

use App\Enums\ElonStatus;
use App\Jobs\DeleteElonFromTelegramChannelJob;
use App\Jobs\SendElonToTelegramChannelJob;
use App\Models\ElonPrice;
use App\Models\MoshinaElon;
use App\Models\User;
use App\Repositories\MoshinaElonRepository;
use Illuminate\Support\Facades\DB;

class MoshinaElonService
{
    public function __construct(
        private readonly MoshinaElonRepository $repository,
        private readonly CarImageService $carImageService,
        private readonly BalanceService $balanceService
    ) {}

    public function create(User $user, array $data): MoshinaElon
    {
        $elon = DB::transaction(function () use ($user, $data) {
            $price = ElonPrice::getElonCreatePrice();
            $this->balanceService->addDebit(
                $user,
                $price,
                'E\'lon uchun yechildi'
            );

            $imageIds = $data['image_ids'] ?? [];
            unset($data['image_ids']);

            $elon = $user->moshinaElons()->create($data);

            if (!empty($imageIds)) {
                $this->carImageService->attachToCar($elon, $imageIds, $user->id);
            }

            return $elon->load('images');
        });

        if ($elon->images->isNotEmpty()) {
            SendElonToTelegramChannelJob::dispatch($elon->id)->delay(now()->addSeconds(5));
        }

        return $elon;
    }

    public function update(MoshinaElon $elon, array $data): MoshinaElon
    {
        $oldStatus = $elon->holati;
        $elon->update($data);

        $newStatus = $elon->holati;
        if ($oldStatus !== $newStatus && in_array($newStatus, [ElonStatus::Sold->value, ElonStatus::Inactive->value])) {
            DeleteElonFromTelegramChannelJob::dispatch($elon->id);
        }

        return $elon->fresh();
    }

    public function delete(MoshinaElon $elon): void
    {
        DeleteElonFromTelegramChannelJob::dispatch($elon->id);
        $elon->delete();
    }
}
