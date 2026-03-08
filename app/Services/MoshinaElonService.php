<?php

namespace App\Services;

use App\Models\MoshinaElon;
use App\Models\User;
use App\Repositories\MoshinaElonRepository;
use Illuminate\Support\Collection;

class MoshinaElonService
{
    public function __construct(
        private readonly MoshinaElonRepository $repository,
        private readonly CarImageService $carImageService
    ) {}

    public function create(User $user, array $data): MoshinaElon
    {
        $imageIds = $data['image_ids'] ?? [];
        unset($data['image_ids']);

        $elon = $user->moshinaElons()->create($data);

        if (!empty($imageIds)) {
            $this->carImageService->attachToCar($elon, $imageIds, $user->id);
        }

        return $elon->load('images');
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
