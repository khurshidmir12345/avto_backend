<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\MoshinaElon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->with(['elon' => fn ($q) => $q->with(['images', 'user', 'category'])])
            ->latest()
            ->paginate(20);

        $elons = $favorites->getCollection()->map(function (Favorite $fav) {
            if (!$fav->elon) return null;
            $elon = $fav->elon;
            $data = $elon->toArray();
            $data['is_favorited'] = true;
            return $data;
        })->filter()->values();

        return response()->json([
            'data' => $elons,
            'meta' => [
                'current_page' => $favorites->currentPage(),
                'last_page' => $favorites->lastPage(),
                'total' => $favorites->total(),
            ],
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'elon_id' => ['required', 'integer', 'exists:moshina_elons,id'],
        ]);

        $userId = $request->user()->id;
        $elonId = $request->elon_id;

        $existing = Favorite::where('user_id', $userId)
            ->where('moshina_elon_id', $elonId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'message' => "Sevimlilardan olib tashlandi",
                'is_favorited' => false,
            ]);
        }

        Favorite::create([
            'user_id' => $userId,
            'moshina_elon_id' => $elonId,
        ]);

        return response()->json([
            'message' => "Sevimlilarga qo'shildi",
            'is_favorited' => true,
        ], 201);
    }

    public function check(Request $request, int $elonId): JsonResponse
    {
        $isFavorited = Favorite::where('user_id', $request->user()->id)
            ->where('moshina_elon_id', $elonId)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }
}
