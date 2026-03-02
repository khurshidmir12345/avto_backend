<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Barcha aktiv kategoriyalar ro'yxati (elonlar soni bilan).
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount(['moshinaElons as elonlar_count' => fn ($q) => $q->where('holati', 'active')])
            ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }
}
