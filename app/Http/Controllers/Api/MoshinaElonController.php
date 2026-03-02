<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoshinaElon\StoreMoshinaElonRequest;
use App\Http\Requests\MoshinaElon\UpdateMoshinaElonRequest;
use App\Http\Requests\MoshinaElon\UploadImagesRequest;
use App\Http\Resources\MoshinaElonCollection;
use App\Http\Resources\MoshinaElonResource;
use App\Models\MoshinaElon;
use App\Models\MoshinaElonImage;
use App\Repositories\MoshinaElonRepository;
use App\Services\MoshinaElonImageService;
use App\Services\MoshinaElonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoshinaElonController extends Controller
{
    public function __construct(
        private readonly MoshinaElonRepository $repository,
        private readonly MoshinaElonService $elonService,
        private readonly MoshinaElonImageService $imageService
    ) {}

    public function index(Request $request): MoshinaElonCollection|JsonResponse
    {
        $filters = $request->only([
            'category_id', 'marka', 'shahar', 'yoqilgi_turi',
            'narx_min', 'narx_max', 'yil_min', 'yil_max',
        ]);

        $query = $this->repository->getFilteredQuery($filters);
        $perPage = $request->get('per_page', config('moshina_elon.per_page'));

        return new MoshinaElonCollection(
            $this->repository->paginate($query, (int) $perPage)
        );
    }

    public function show(MoshinaElon $moshinaElon): JsonResponse
    {
        $moshinaElon->load(['user:id,name,phone', 'category:id,name,slug,icon', 'images']);

        return response()->json([
            'elon' => new MoshinaElonResource($moshinaElon),
        ]);
    }

    public function store(StoreMoshinaElonRequest $request): JsonResponse
    {
        $elon = $this->elonService->create($request->user(), $request->validated());

        return response()->json([
            'message' => 'E\'lon muvaffaqiyatli yaratildi',
            'elon' => new MoshinaElonResource($elon),
        ], 201);
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

    public function myElonlar(Request $request): MoshinaElonCollection
    {
        $perPage = $request->get('per_page', config('moshina_elon.per_page'));

        $elonlar = $request->user()
            ->moshinaElons()
            ->with(['category:id,name,slug,icon', 'images'])
            ->latest()
            ->paginate((int) $perPage);

        return new MoshinaElonCollection($elonlar);
    }

    public function uploadImagesFirst(UploadImagesRequest $request): JsonResponse
    {
        $images = $this->imageService->uploadForUser(
            $request->user()->id,
            $request->file('images', [])
        );

        return response()->json([
            'message' => $images->count() . ' ta rasm muvaffaqiyatli yuklandi',
            'images' => $images->map(fn ($img) => ['id' => $img->id, 'url' => $img->public_url, 'sort_order' => $img->sort_order]),
        ], 201);
    }

    public function uploadImages(UploadImagesRequest $request, MoshinaElon $moshinaElon): JsonResponse
    {
        $this->authorize('manageImages', $moshinaElon);

        $images = $this->imageService->uploadForElon(
            $moshinaElon,
            $request->file('images', []),
            $request->user()->id
        );

        return response()->json([
            'message' => $images->count() . ' ta rasm muvaffaqiyatli yuklandi',
            'images' => $images->map(fn ($img) => ['id' => $img->id, 'url' => $img->public_url, 'sort_order' => $img->sort_order]),
        ], 201);
    }

    public function deleteImage(Request $request, MoshinaElon $moshinaElon, MoshinaElonImage $image): JsonResponse
    {
        $this->authorize('manageImages', $moshinaElon);

        if ($image->moshina_elon_id !== $moshinaElon->id) {
            return response()->json(['message' => 'Rasm bu e\'longa tegishli emas'], 404);
        }

        $image->delete();

        return response()->json(['message' => 'Rasm muvaffaqiyatli o\'chirildi']);
    }

    public function deleteOrphanImage(Request $request, MoshinaElonImage $image): JsonResponse
    {
        $this->authorize('deleteOrphanImage', $image);

        $image->delete();

        return response()->json(['message' => 'Rasm muvaffaqiyatli o\'chirildi']);
    }
}
