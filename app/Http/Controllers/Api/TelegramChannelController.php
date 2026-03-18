<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelegramChannel;
use Illuminate\Http\JsonResponse;

class TelegramChannelController extends Controller
{
    /**
     * Global Telegram kanallar ro'yxati (public).
     * GET /api/telegram-channels
     */
    public function index(): JsonResponse
    {
        $channels = TelegramChannel::active()
            ->ordered()
            ->get([
                'id',
                'name',
                'username',
                'description',
                'link',
                'avatar_path',
                'avatar_disk',
                'member_count',
            ]);

        return response()->json([
            'data' => $channels,
        ]);
    }
}
