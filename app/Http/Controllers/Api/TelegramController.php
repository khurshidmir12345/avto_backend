<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelegramBot;
use App\Models\TelegramLinkToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    /**
     * Profil ulash uchun bot link va ma'lumot.
     * GET /api/telegram/link-info
     */
    public function linkInfo(): JsonResponse
    {
        $bot = TelegramBot::where('bot_type', 'set_profile_bot')->first();

        if (!$bot) {
            return response()->json([
                'message' => 'Telegram bot sozlanmagan',
                'bot_username' => null,
                'bot_link' => null,
            ], 404);
        }

        $botUsername = $this->extractBotUsername($bot);
        $botLink = $botUsername ? "https://t.me/{$botUsername}" : null;

        return response()->json([
            'message' => 'OK',
            'bot_username' => $botUsername,
            'bot_link' => $botLink,
            'instructions' => 'Telegram hisobingizni Avto Vodiy ilovasiga ulash uchun botga kiring va /start bosing. Bot sizga ulash linkini beradi.',
        ]);
    }

    /**
     * Token orqali Telegram hisobini profilga ulash.
     * POST /api/telegram/link
     */
    public function link(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string', 'max:64'],
        ]);

        $linkToken = TelegramLinkToken::where('token', $request->token)->first();

        if (!$linkToken || !$linkToken->isValid()) {
            return response()->json([
                'message' => 'Link muddati o\'tgan yoki noto\'g\'ri. Iltimos, botda qayta /start bosing.',
            ], 422);
        }

        $user = $request->user();

        $existingUser = User::where('telegram_user_id', $linkToken->telegram_user_id)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return response()->json([
                'message' => 'Bu Telegram hisob boshqa foydalanuvchiga ulangan.',
            ], 409);
        }

        $user->update([
            'telegram_user_id' => $linkToken->telegram_user_id,
            'telegram_username' => $linkToken->telegram_username,
            'telegram_first_name' => $linkToken->telegram_first_name,
            'telegram_last_name' => $linkToken->telegram_last_name,
        ]);

        $linkToken->delete();

        return response()->json([
            'message' => 'Telegram hisobingiz muvaffaqiyatli ulandi',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Telegram hisobini profildan uzish.
     * DELETE /api/auth/telegram/unlink
     */
    public function unlink(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->telegram_user_id) {
            return response()->json([
                'message' => 'Telegram hisob ulanmagan',
            ], 422);
        }

        $user->update([
            'telegram_user_id' => null,
            'telegram_username' => null,
            'telegram_first_name' => null,
            'telegram_last_name' => null,
        ]);

        return response()->json([
            'message' => 'Telegram hisob muvaffaqiyatli uzildi',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Support bot username va link (public, auth kerak emas).
     * GET /api/support/bot-info
     */
    public function supportBotInfo(): JsonResponse
    {
        $bot = TelegramBot::supportBot();

        if (!$bot) {
            return response()->json([
                'bot_username' => null,
                'bot_link' => null,
            ]);
        }

        $botUsername = $this->extractBotUsername($bot);

        return response()->json([
            'bot_username' => $botUsername,
            'bot_link' => $botUsername ? "https://t.me/{$botUsername}" : null,
        ]);
    }

    private function extractBotUsername(TelegramBot $bot): ?string
    {
        $cacheKey = 'telegram_bot_username_' . $bot->id;

        return Cache::remember($cacheKey, now()->addDay(), function () use ($bot) {
            $response = Http::get("https://api.telegram.org/bot{$bot->token}/getMe");

            if ($response->successful()) {
                $data = $response->json();
                return $data['result']['username'] ?? null;
            }

            return null;
        });
    }
}
