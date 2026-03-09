<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TelegramWebhookController extends Controller
{
    public function __construct(
        private readonly TelegramBotService $telegramBotService
    ) {}

    /**
     * Telegram webhook — set_profile_bot uchun.
     * Route: POST /api/telegram/webhook/set_profile_bot
     */
    public function __invoke(Request $request, string $botType): Response
    {
        $bot = TelegramBot::where('bot_type', $botType)->first();

        if (!$bot || $botType !== 'set_profile_bot') {
            return response('', 404);
        }

        $this->telegramBotService->handleSetProfileBotMessage($request->all());

        return response('', 200);
    }
}
