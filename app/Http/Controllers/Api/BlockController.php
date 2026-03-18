<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlockedUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $blockedUsers = $request->user()
            ->blockedUsers()
            ->select('users.id', 'users.name', 'users.phone', 'users.avatar_path', 'users.avatar_disk')
            ->latest('blocked_users.created_at')
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'phone' => $u->phone,
                'avatar_url' => $u->avatar_url,
                'blocked_at' => $u->pivot->created_at,
            ]);

        return response()->json(['data' => $blockedUsers]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'blocked_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = $request->user();
        $blockedUserId = (int) $request->blocked_user_id;

        if ($blockedUserId === $user->id) {
            return response()->json(['message' => 'O\'zingizni bloklashning iloji yo\'q'], 422);
        }

        if ($user->hasBlocked($blockedUserId)) {
            return response()->json(['message' => 'Bu foydalanuvchi allaqachon bloklangan'], 409);
        }

        BlockedUser::create([
            'user_id' => $user->id,
            'blocked_user_id' => $blockedUserId,
        ]);

        return response()->json([
            'message' => 'Foydalanuvchi bloklandi',
        ], 201);
    }

    public function destroy(Request $request, int $blockedUserId): JsonResponse
    {
        $deleted = BlockedUser::where('user_id', $request->user()->id)
            ->where('blocked_user_id', $blockedUserId)
            ->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Bloklangan foydalanuvchi topilmadi'], 404);
        }

        return response()->json([
            'message' => 'Foydalanuvchi blokdan chiqarildi',
        ]);
    }
}
