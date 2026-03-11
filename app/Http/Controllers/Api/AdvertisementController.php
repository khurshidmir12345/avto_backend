<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\AdvertisementService;
use App\Services\R2ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function __construct(
        private readonly AdvertisementService $advertisementService,
        private readonly R2ImageService $r2ImageService
    ) {}

    /**
     * Faol reklamalar (public).
     */
    public function index(): JsonResponse
    {
        $ads = Advertisement::active()
            ->with('user:id,name')
            ->orderByDesc('started_at')
            ->limit(20)
            ->get()
            ->map(fn (Advertisement $ad) => $this->formatAd($ad));

        return response()->json(['data' => $ads]);
    }

    /**
     * Reklama narxi.
     */
    public function price(): JsonResponse
    {
        $dailyPrice = Advertisement::getReklamaPrice();
        $todayCount = Advertisement::todayApprovedCount();

        return response()->json([
            'daily_price' => $dailyPrice,
            'max_daily_ads' => Advertisement::MAX_DAILY_ADS,
            'today_approved_count' => $todayCount,
            'slots_remaining' => max(0, Advertisement::MAX_DAILY_ADS - $todayCount),
        ]);
    }

    /**
     * Foydalanuvchi o'z reklamalari.
     */
    public function myAds(Request $request): JsonResponse
    {
        $ads = Advertisement::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        $items = collect($ads->items())->map(fn (Advertisement $ad) => $this->formatAd($ad));

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $ads->currentPage(),
                'last_page' => $ads->lastPage(),
                'per_page' => $ads->perPage(),
                'total' => $ads->total(),
            ],
        ]);
    }

    /**
     * Presigned URL olish (reklama rasmi uchun).
     */
    public function presignedUrl(Request $request): JsonResponse
    {
        $request->validate([
            'content_type' => 'required|string|in:image/jpeg,image/jpg,image/png,image/webp,image/gif,image/bmp,image/heic,image/heif,image/tiff',
        ]);

        $user = $request->user();
        $ext = match ($request->content_type) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/heic' => 'heic',
            'image/heif' => 'heif',
            'image/tiff' => 'tiff',
            default => 'jpg',
        };

        $imageKey = "ads/{$user->id}/" . \Illuminate\Support\Str::random(12) . ".{$ext}";
        $uploadUrl = $this->r2ImageService->createPresignedUploadUrl($imageKey, $request->content_type);

        return response()->json([
            'image_key' => $imageKey,
            'upload_url' => $uploadUrl,
        ]);
    }

    /**
     * Yangi reklama yaratish.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'image_key' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'days' => 'required|integer|min:1|max:30',
        ]);

        $todayCount = Advertisement::todayApprovedCount();
        if ($todayCount >= Advertisement::MAX_DAILY_ADS) {
            return response()->json([
                'message' => 'Bugun uchun reklama limiti tugagan (maksimum ' . Advertisement::MAX_DAILY_ADS . ' ta)',
            ], 422);
        }

        try {
            $ad = $this->advertisementService->create($request->user(), $request->only([
                'title', 'description', 'image_key', 'link', 'days',
            ]));

            return response()->json([
                'message' => 'Reklama yaratildi. Admin tasdiqlashini kuting.',
                'advertisement' => $this->formatAd($ad),
            ], 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Reklamani qayta faollashtirish (expired/rejected).
     */
    public function reactivate(Request $request, Advertisement $advertisement): JsonResponse
    {
        if ($advertisement->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Ruxsat yo\'q'], 403);
        }

        if (! in_array($advertisement->status, [Advertisement::STATUS_EXPIRED, Advertisement::STATUS_REJECTED])) {
            return response()->json(['message' => 'Faqat tugagan yoki rad etilgan reklamani qayta faollashtirish mumkin'], 422);
        }

        try {
            $ad = $this->advertisementService->reactivate($advertisement, $request->user());

            return response()->json([
                'message' => 'Reklama qayta yuborildi. Admin tasdiqlashini kuting.',
                'advertisement' => $this->formatAd($ad),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Reklamani o'chirish (faqat draft/pending).
     */
    public function destroy(Request $request, Advertisement $advertisement): JsonResponse
    {
        if ($advertisement->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Ruxsat yo\'q'], 403);
        }

        if ($advertisement->status === Advertisement::STATUS_PENDING) {
            $this->advertisementService->reject($advertisement, 'Foydalanuvchi tomonidan bekor qilindi');
        }

        $advertisement->delete();

        return response()->json(['message' => 'Reklama o\'chirildi']);
    }

    /**
     * Ko'rishlar sonini oshirish.
     */
    public function trackView(Advertisement $advertisement): JsonResponse
    {
        $this->advertisementService->incrementViews($advertisement);

        return response()->json(['success' => true]);
    }

    private function formatAd(Advertisement $ad): array
    {
        return [
            'id' => $ad->id,
            'user_id' => $ad->user_id,
            'user_name' => $ad->user?->name,
            'title' => $ad->title,
            'description' => $ad->description,
            'image_url' => $ad->image_url,
            'image_key' => $ad->image_key,
            'link' => $ad->link,
            'status' => $ad->status,
            'days' => $ad->days,
            'daily_price' => $ad->daily_price,
            'total_price' => $ad->total_price,
            'views' => $ad->views,
            'started_at' => $ad->started_at?->toIso8601String(),
            'expires_at' => $ad->expires_at?->toIso8601String(),
            'rejection_reason' => $ad->rejection_reason,
            'created_at' => $ad->created_at?->toIso8601String(),
            'is_active' => $ad->isActive(),
        ];
    }
}
