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

    public function __invoke(Request $request, string $botType): Response
    {
        $bot = TelegramBot::where('bot_type', $botType)->first();

        if (!$bot) {
            return response('', 404);
        }

        match ($botType) {
            'set_profile_bot' => $this->telegramBotService->handleSetProfileBotMessage($request->all()),
            'support' => $this->telegramBotService->handleSupportBotMessage($bot, $request->all()),
            default => null,
        };

        return response('', 200);
    }
}
