<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserTelegramChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserTelegramChannelController extends Controller
{
    private const MAX_CHANNELS_PER_USER = 5;

    /**
     * Foydalanuvchining barcha kanallari.
     * GET /api/auth/user-channels
     */
    public function index(Request $request): JsonResponse
    {
        $channels = $request->user()
            ->telegramChannels()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (UserTelegramChannel $ch) => $this->formatChannel($ch));

        return response()->json([
            'data' => $channels,
            'max_channels' => self::MAX_CHANNELS_PER_USER,
        ]);
    }

    /**
     * Yangi kanal qo'shish.
     * POST /api/auth/user-channels
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->telegramChannels()->count() >= self::MAX_CHANNELS_PER_USER) {
            return response()->json([
                'message' => 'Maksimal ' . self::MAX_CHANNELS_PER_USER . ' ta kanal qo\'shish mumkin',
            ], 422);
        }

        $validated = $request->validate([
            'bot_token' => ['required', 'string', 'max:200'],
            'chat_id' => ['required', 'string', 'max:100'],
            'channel_name' => ['nullable', 'string', 'max:100'],
            'channel_username' => ['nullable', 'string', 'max:100'],
            'message_template' => ['nullable', 'string', 'max:2000'],
            'footer_text' => ['nullable', 'string', 'max:500'],
        ]);

        $botCheck = $this->verifyBotToken($validated['bot_token']);
        if (!$botCheck['ok']) {
            return response()->json([
                'message' => 'Bot token noto\'g\'ri yoki bot ishlamayapti',
                'error_detail' => $botCheck['description'] ?? null,
            ], 422);
        }

        $chatCheck = $this->verifyChatAccess($validated['bot_token'], $validated['chat_id']);
        if (!$chatCheck['ok']) {
            return response()->json([
                'message' => 'Bot bu kanalga yoza olmaydi. Botni kanalga admin sifatida qo\'shing.',
                'error_detail' => $chatCheck['description'] ?? null,
            ], 422);
        }

        if (empty($validated['channel_name']) && !empty($chatCheck['title'])) {
            $validated['channel_name'] = $chatCheck['title'];
        }
        if (empty($validated['channel_username']) && !empty($chatCheck['username'])) {
            $validated['channel_username'] = $chatCheck['username'];
        }

        $channel = $user->telegramChannels()->create($validated);

        return response()->json([
            'message' => 'Kanal muvaffaqiyatli qo\'shildi',
            'channel' => $this->formatChannel($channel),
        ], 201);
    }

    /**
     * Kanalni tahrirlash.
     * PUT /api/auth/user-channels/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $channel = $request->user()->telegramChannels()->findOrFail($id);

        $validated = $request->validate([
            'bot_token' => ['sometimes', 'string', 'max:200'],
            'chat_id' => ['sometimes', 'string', 'max:100'],
            'channel_name' => ['nullable', 'string', 'max:100'],
            'channel_username' => ['nullable', 'string', 'max:100'],
            'message_template' => ['nullable', 'string', 'max:2000'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['bot_token'])) {
            $botCheck = $this->verifyBotToken($validated['bot_token']);
            if (!$botCheck['ok']) {
                return response()->json([
                    'message' => 'Bot token noto\'g\'ri yoki bot ishlamayapti',
                ], 422);
            }
        }

        $token = $validated['bot_token'] ?? $channel->bot_token;
        $chatId = $validated['chat_id'] ?? $channel->chat_id;

        if (isset($validated['bot_token']) || isset($validated['chat_id'])) {
            $chatCheck = $this->verifyChatAccess($token, $chatId);
            if (!$chatCheck['ok']) {
                return response()->json([
                    'message' => 'Bot bu kanalga yoza olmaydi. Botni admin sifatida qo\'shing.',
                ], 422);
            }
        }

        $channel->update($validated);

        return response()->json([
            'message' => 'Kanal yangilandi',
            'channel' => $this->formatChannel($channel->fresh()),
        ]);
    }

    /**
     * Kanalni o'chirish.
     * DELETE /api/auth/user-channels/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $channel = $request->user()->telegramChannels()->findOrFail($id);
        $channel->delete();

        return response()->json([
            'message' => 'Kanal o\'chirildi',
        ]);
    }

    /**
     * Kanalga test xabar yuborish.
     * POST /api/auth/user-channels/{id}/test
     */
    public function test(Request $request, int $id): JsonResponse
    {
        $channel = $request->user()->telegramChannels()->findOrFail($id);

        try {
            $response = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$channel->bot_token}/sendMessage",
                [
                    'chat_id' => $channel->chat_id,
                    'text' => "✅ Avto Vodiy — test xabar.\n\nKanal muvaffaqiyatli ulangan!",
                    'parse_mode' => 'HTML',
                ]
            );

            if ($response->successful() && data_get($response->json(), 'ok')) {
                $channel->update([
                    'last_error_at' => null,
                    'last_error_message' => null,
                ]);

                return response()->json([
                    'message' => 'Test xabar muvaffaqiyatli yuborildi',
                ]);
            }

            $desc = data_get($response->json(), 'description', 'Noma\'lum xato');
            $channel->update([
                'last_error_at' => now(),
                'last_error_message' => $desc,
            ]);

            return response()->json([
                'message' => 'Xabar yuborib bo\'lmadi: ' . $desc,
            ], 422);
        } catch (\Throwable $e) {
            Log::error('UserTelegramChannel test xatosi', [
                'channel_id' => $channel->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Serverga ulanib bo\'lmadi',
            ], 500);
        }
    }

    private function verifyBotToken(string $token): array
    {
        try {
            $response = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getMe");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'ok' => $data['ok'] ?? false,
                    'username' => data_get($data, 'result.username'),
                ];
            }

            return ['ok' => false, 'description' => 'Bot token noto\'g\'ri'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    private function verifyChatAccess(string $token, string $chatId): array
    {
        try {
            $response = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getChat", [
                    'chat_id' => $chatId,
                ]);

            if (!$response->successful()) {
                return ['ok' => false, 'description' => 'Kanal topilmadi yoki bot admin emas'];
            }

            $data = $response->json();
            $title = data_get($data, 'result.title');
            $username = data_get($data, 'result.username');

            $memberResponse = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getChatMember", [
                    'chat_id' => $chatId,
                    'user_id' => $this->getBotUserId($token),
                ]);

            if ($memberResponse->successful()) {
                $status = data_get($memberResponse->json(), 'result.status');
                if (!in_array($status, ['administrator', 'creator'])) {
                    return [
                        'ok' => false,
                        'description' => 'Bot kanalda admin emas. Botni admin qiling.',
                        'title' => $title,
                        'username' => $username,
                    ];
                }
            }

            return [
                'ok' => true,
                'title' => $title,
                'username' => $username,
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    private function getBotUserId(string $token): ?int
    {
        try {
            $response = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getMe");

            if ($response->successful()) {
                return data_get($response->json(), 'result.id');
            }
        } catch (\Throwable) {}

        return null;
    }

    private function formatChannel(UserTelegramChannel $channel): array
    {
        return [
            'id' => $channel->id,
            'chat_id' => $channel->chat_id,
            'channel_name' => $channel->channel_name,
            'channel_username' => $channel->channel_username,
            'message_template' => $channel->message_template,
            'footer_text' => $channel->footer_text,
            'is_active' => $channel->is_active,
            'last_error_at' => $channel->last_error_at?->toIso8601String(),
            'last_error_message' => $channel->last_error_message,
            'created_at' => $channel->created_at?->toIso8601String(),
        ];
    }
}
