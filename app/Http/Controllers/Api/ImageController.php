<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\PresignedUrlRequest;
use App\Http\Requests\Image\SaveImageRequest;
use App\Models\CarImage;
use App\Models\MoshinaElon;
use App\Services\CarImageService;
use App\Services\R2ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(
        private readonly R2ImageService $r2Service,
        private readonly CarImageService $carImageService
    ) {}

    /**
     * POST /api/images/presigned-url
     * R2 uchun presigned upload URL lar yaratadi.
     */
    public function presignedUrl(PresignedUrlRequest $request): JsonResponse
    {
        $user = $request->user();
        $carId = $request->validated('car_id');
        $contentTypes = $request->validated('content_types');

        if ($carId !== null) {
            $elon = MoshinaElon::find($carId);
            if (!$elon || $elon->user_id !== $user->id) {
                return response()->json(['message' => 'E\'lon topilmadi'], 404);
            }
        }

        $urls = [];
        foreach ($contentTypes as $i => $contentType) {
            $ext = $this->contentTypeToExt($contentType);
            $imageKey = $this->r2Service->generateImageKey($carId, $user->id, $ext);
            $uploadUrl = $this->r2Service->createPresignedUploadUrl($imageKey, $contentType);
            $urls[] = [
                'image_key' => $imageKey,
                'upload_url' => $uploadUrl,
            ];
        }

        return response()->json([
            'message' => 'Presigned URL lar tayyor',
            'urls' => $urls,
        ]);
    }

    /**
     * POST /api/images/save
     * Client upload qilgan image_key larni database ga saqlaydi.
     */
    public function save(SaveImageRequest $request): JsonResponse
    {
        $images = $this->carImageService->saveImageKeys(
            $request->user(),
            $request->validated('image_keys'),
            $request->validated('car_id')
        );

        return response()->json([
            'message' => $images->count() . ' ta rasm saqlandi',
            'images' => $images->map(fn (CarImage $img) => [
                'id' => $img->id,
                'image_key' => $img->image_key,
                'original' => $img->original_url,
                'thumb' => $img->thumb_url,
                'sort_order' => $img->sort_order,
            ]),
        ], 201);
    }

    /**
     * DELETE /api/images/{image}
     * Orphan rasmni o'chirish (e'lon yaratilishidan oldin yuklangan).
     */
    public function deleteOrphanImage(Request $request, CarImage $image): JsonResponse
    {
        $this->authorize('deleteOrphanImage', $image);

        $image->delete();

        return response()->json(['message' => 'Rasm muvaffaqiyatli o\'chirildi']);
    }

    private function contentTypeToExt(string $contentType): string
    {
        return match ($contentType) {
            'image/png' => 'png',
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/heic' => 'heic',
            'image/heif' => 'heif',
            'image/tiff' => 'tiff',
            'image/svg+xml' => 'svg',
            default => 'jpg',
        };
    }
}
