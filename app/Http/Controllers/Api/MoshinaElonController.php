<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoshinaElon\MyElonlarRequest;
use App\Http\Requests\MoshinaElon\StoreMoshinaElonRequest;
use App\Http\Requests\MoshinaElon\UpdateMoshinaElonRequest;
use App\Http\Resources\CarImageResource;
use App\Http\Resources\MoshinaElonCollection;
use App\Http\Resources\MoshinaElonResource;
use App\Models\CarImage;
use App\Models\MoshinaElon;
use App\Repositories\MoshinaElonRepository;
use App\Services\CarImageService;
use App\Services\MoshinaElonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoshinaElonController extends Controller
{
    public function __construct(
        private readonly MoshinaElonRepository $repository,
        private readonly MoshinaElonService $elonService,
        private readonly CarImageService $carImageService
    ) {}

    public function index(Request $request): MoshinaElonCollection|JsonResponse
    {
        $filters = $request->only([
            'category_id', 'marka', 'model', 'search', 'shahar', 'yoqilgi_turi',
            'narx_min', 'narx_max', 'yil_min', 'yil_max',
        ]);

        $query = $this->repository->getFilteredQuery($filters);

        if ($request->user()) {
            $blockedIds = $request->user()->getBlockedUserIds();
            if (!empty($blockedIds)) {
                $query->whereNotIn('user_id', $blockedIds);
            }
        }

        $perPage = $request->get('per_page', config('moshina_elon.per_page'));

        return new MoshinaElonCollection(
            $this->repository->paginate($query, (int) $perPage)
        );
    }

    public function show(MoshinaElon $moshinaElon): JsonResponse
    {
        $moshinaElon->load(['user:id,name,phone,avatar_path,avatar_disk,telegram_username', 'category:id,name,slug,icon', 'images']);

        return response()->json([
            'elon' => new MoshinaElonResource($moshinaElon),
        ]);
    }

    public function store(StoreMoshinaElonRequest $request): JsonResponse
    {
        try {
            $elon = $this->elonService->create($request->user(), $request->validated());

            return response()->json([
                'message' => 'E\'lon muvaffaqiyatli yaratildi',
                'elon' => new MoshinaElonResource($elon),
            ], 201);
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'Balans yetarli emas') {
                return response()->json([
                    'message' => 'Balans yetarli emas. E\'lon yaratish uchun hisobingizni to\'ldiring.',
                ], 422);
            }
            throw $e;
        }
    }

    public function update(UpdateMoshinaElonRequest $request, MoshinaElon $moshinaElon): JsonResponse
    {
        $elon = $this->elonService->update($moshinaElon, $request->validated());

        return response()->json([
            'message' => 'E\'lon muvaffaqiyatli yangilandi',
            'elon' => new MoshinaElonResource($elon),
        ]);
    }

    public function destroy(Request $request, MoshinaElon $moshinaElon): JsonResponse
    {
        $this->authorize('delete', $moshinaElon);

        $this->elonService->delete($moshinaElon);

        return response()->json([
            'message' => 'E\'lon muvaffaqiyatli o\'chirildi',
        ]);
    }

    public function myElonlar(MyElonlarRequest $request): MoshinaElonCollection
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? config('moshina_elon.per_page'));

        $elonlar = $request->user()
            ->moshinaElons()
            ->with(['category:id,name,slug,icon', 'images'])
            ->latest()
            ->paginate($perPage);

        return new MoshinaElonCollection($elonlar);
    }

    /**
     * GET /api/elonlar/{id}/images
     */
    public function images(MoshinaElon $moshinaElon): JsonResponse
    {
        $images = $moshinaElon->images()->orderBy('sort_order')->get();

        return response()->json([
            'images' => CarImageResource::collection($images),
        ]);
    }

    /**
     * DELETE /api/elonlar/{id}/images/{imageId}
     */
    public function deleteImage(Request $request, MoshinaElon $moshinaElon, CarImage $image): JsonResponse
    {
        $this->authorize('manageImages', $moshinaElon);

        if ($image->car_id !== $moshinaElon->id) {
            return response()->json(['message' => 'Rasm bu e\'longa tegishli emas'], 404);
        }

        $image->delete();

        return response()->json(['message' => 'Rasm muvaffaqiyatli o\'chirildi']);
    }

    /**
     * PUT /api/elonlar/{id}/images/reorder
     */
    public function reorderImages(Request $request, MoshinaElon $moshinaElon): JsonResponse
    {
        $this->authorize('manageImages', $moshinaElon);

        $request->validate([
            'image_ids' => ['required', 'array', 'min:1'],
            'image_ids.*' => ['integer', 'exists:car_images,id'],
        ]);

        $this->carImageService->reorder($moshinaElon, $request->input('image_ids'));

        return response()->json(['message' => 'Tartib yangilandi']);
    }

}
