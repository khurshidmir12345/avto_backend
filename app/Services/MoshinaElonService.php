<?php

namespace App\Services;

use App\Models\ElonPrice;
use App\Models\MoshinaElon;
use App\Models\User;
use App\Repositories\MoshinaElonRepository;
use Illuminate\Support\Collection;
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
        return DB::transaction(function () use ($user, $data) {
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
    }

    public function update(MoshinaElon $elon, array $data): MoshinaElon
    {
        $elon->update($data);

        return $elon->fresh();
    }

    public function delete(MoshinaElon $elon): void
    {
        $elon->delete();
    }
}
