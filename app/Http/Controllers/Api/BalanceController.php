<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElonPrice;
use App\Models\UserBalanceHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * Foydalanuvchi balans tarixini qaytaradi (pagination bilan).
     */
    public function history(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->get('per_page', 15), 50));

        $history = UserBalanceHistory::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'data' => $history->items(),
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ],
        ]);
    }

    /**
     * E'lon yaratish narxini qaytaradi.
     */
    public function elonCreatePrice(): JsonResponse
    {
        return response()->json([
            'amount' => ElonPrice::getElonCreatePrice(),
        ]);
    }
}
