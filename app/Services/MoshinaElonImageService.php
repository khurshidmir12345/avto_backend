<?php

namespace App\Services;

use App\Models\MoshinaElon;
use App\Models\MoshinaElonImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class MoshinaElonImageService
{
    private readonly string $disk;
    private readonly int $maxSizeKb;
    private readonly array $mimes;

    public function __construct()
    {
        $this->disk = config('moshina_elon.images.disk', 'r2');
        $this->maxSizeKb = config('moshina_elon.images.max_size_kb', 20480);
        $this->mimes = config('moshina_elon.images.mimes', ['jpeg', 'jpg', 'png', 'webp']);
    }

    public function getValidationRules(bool $required = true): array
    {
        $rules = [
            'images' => [$required ? 'required' : 'nullable', 'array'],
            'images.*' => [
                'required',
                'image',
                'mimes:' . implode(',', $this->mimes),
                'max:' . $this->maxSizeKb,
            ],
        ];

        return $rules;
    }

    public function getValidationMessages(): array
    {
        return [
            'images.required' => 'Kamida bitta rasm yuborilishi kerak',
            'images.*.image' => 'Fayl rasm bo\'lishi kerak',
            'images.*.mimes' => 'Rasm formati: ' . implode(', ', $this->mimes),
            'images.*.max' => "Har bir rasm maksimum {$this->maxSizeKb}KB bo'lishi kerak",
        ];
    }

    public function uploadForUser(int $userId, array $files): Collection
    {
        $pathPrefix = config('moshina_elon.images.path_prefix_user', 'uploads');
        $storagePath = "{$pathPrefix}/{$userId}";

        return $this->uploadFiles($files, $storagePath, [
            'user_id' => $userId,
            'moshina_elon_id' => null,
        ], fn () => MoshinaElonImage::where('user_id', $userId)->whereNull('moshina_elon_id')->max('sort_order') ?? 0);
    }

    public function uploadForElon(MoshinaElon $elon, array $files, int $userId): Collection
    {
        $pathPrefix = config('moshina_elon.images.path_prefix_elon', 'elonlar');
        $storagePath = "{$pathPrefix}/{$elon->id}";

        $sortOrder = $elon->images()->max('sort_order') ?? 0;

        return $this->uploadFiles($files, $storagePath, [
            'user_id' => $userId,
            'moshina_elon_id' => $elon->id,
        ], fn () => ++$sortOrder);
    }

    private function uploadFiles(array $files, string $storagePath, array $extraData, callable $getSortOrder): Collection
    {
        $uploaded = collect();
        $sortOrder = $getSortOrder();

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store($storagePath, $this->disk);

            if (!$path) {
                throw new \RuntimeException('Rasm yuklashda xatolik. Storage sozlamalarini tekshiring.');
            }

            $url = $this->buildImageUrl($path);

            $image = MoshinaElonImage::create(array_merge($extraData, [
                'path' => $path,
                'disk' => $this->disk,
                'url' => $url,
                'sort_order' => ++$sortOrder,
            ]));

            $uploaded->push($image);
        }

        return $uploaded;
    }

    /**
     * Rasm URL ni qurish. R2_PUBLIC_URL bo'sh bo'lsa, Laravel proxy ishlatiladi.
     */
    private function buildImageUrl(string $path): string
    {
        $publicUrl = config('filesystems.disks.r2.url') ?? env('R2_PUBLIC_URL');

        if (!empty($publicUrl)) {
            return rtrim($publicUrl, '/') . '/' . ltrim($path, '/');
        }

        return rtrim(config('app.url'), '/') . '/media/' . ltrim($path, '/');
    }

    public function attachImagesToElon(MoshinaElon $elon, array $imageIds, int $userId): void
    {
        MoshinaElonImage::whereIn('id', $imageIds)
            ->where('user_id', $userId)
            ->whereNull('moshina_elon_id')
            ->orderBy('sort_order')
            ->get()
            ->each(function (MoshinaElonImage $image, int $index) use ($elon) {
                $image->update([
                    'moshina_elon_id' => $elon->id,
                    'sort_order' => $index + 1,
                ]);
            });
    }
}
