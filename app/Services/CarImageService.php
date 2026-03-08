<?php

namespace App\Services;

use App\Models\CarImage;
use App\Models\MoshinaElon;
use App\Models\User;
use Illuminate\Support\Collection;

class CarImageService
{
    public function saveImageKeys(User $user, array $imageKeys, ?int $carId): Collection
    {
        $saved = collect();
        $sortOrder = $this->getNextSortOrder($carId, $user->id);

        foreach ($imageKeys as $key) {
            $image = CarImage::create([
                'car_id' => $carId,
                'user_id' => $user->id,
                'image_key' => $key,
                'sort_order' => $sortOrder++,
            ]);
            $saved->push($image);
        }

        return $saved;
    }

    public function attachToCar(MoshinaElon $elon, array $imageIds, int $userId): void
    {
        CarImage::whereIn('id', $imageIds)
            ->where('user_id', $userId)
            ->whereNull('car_id')
            ->orderBy('sort_order')
            ->get()
            ->each(function (CarImage $image, int $index) use ($elon) {
                $image->update([
                    'car_id' => $elon->id,
                    'sort_order' => $index + 1,
                ]);
            });
    }

    public function reorder(MoshinaElon $elon, array $imageIds): void
    {
        foreach ($imageIds as $index => $id) {
            CarImage::where('id', $id)
                ->where('car_id', $elon->id)
                ->update(['sort_order' => $index + 1]);
        }
    }

    private function getNextSortOrder(?int $carId, int $userId): int
    {
        $query = $carId !== null
            ? CarImage::where('car_id', $carId)
            : CarImage::where('user_id', $userId)->whereNull('car_id');

        return ($query->max('sort_order') ?? 0) + 1;
    }
}
