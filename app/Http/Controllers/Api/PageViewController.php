<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PageViewController extends Controller
{
    private const ALLOWED_PAGES = [
        'home',
        'listings',
        'elon_detail',
        'chat',
        'profile',
    ];

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'required|string|in:' . implode(',', self::ALLOWED_PAGES),
            'device_id' => 'nullable|string|max:64',
            'platform' => 'nullable|string|in:ios,android',
        ]);

        PageView::create([
            'page' => $request->input('page'),
            'device_id' => $request->input('device_id'),
            'platform' => $request->input('platform'),
            'view_date' => Carbon::today()->toDateString(),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
