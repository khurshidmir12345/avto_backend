<?php

namespace App\Services;

use App\Enums\BotType;
use App\Models\MoshinaElon;
use App\Models\TelegramBot;
use App\Models\TelegramSentElon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramChannelService
{
    private const API_BASE = 'https://api.telegram.org/bot';

    public function __construct(
        private readonly ImageCollageService $collageService,
    ) {}

    /**
     * E'lonni Telegram kanalga yuboradi.
     *
     * @return TelegramSentElon|null Yuborilgan xabar yoki null (xato bo'lsa)
     */
    public function sendElon(MoshinaElon $elon): ?TelegramSentElon
    {
        $bot = TelegramBot::elonSendChannel();

        if (! $bot || ! $bot->channel_id) {
            Log::warning('TelegramChannelService: elon_send_channel bot yoki channel_id topilmadi');
            return null;
        }

        $elon->loadMissing(['images', 'user']);

        $images = $elon->images;
        if ($images->isEmpty()) {
            Log::info("TelegramChannelService: Elon #{$elon->id} da rasmlar yo'q, yuborilmadi");
            return null;
        }

        $collageData = $this->buildCollage($images);
        if (! $collageData) {
            Log::error("TelegramChannelService: Elon #{$elon->id} uchun collage yaratib bo'lmadi");
            return null;
        }

        $caption = $this->formatCaption($elon);

        $response = $this->sendPhoto(
            $bot->token,
            $bot->channel_id,
            $collageData,
            $caption,
        );

        if (! $response) {
            return null;
        }

        $messageId = data_get($response, 'result.message_id');
        if (! $messageId) {
            Log::error('TelegramChannelService: message_id olinmadi', ['response' => $response]);
            return null;
        }

        return TelegramSentElon::create([
            'moshina_elon_id' => $elon->id,
            'user_id' => $elon->user_id,
            'telegram_bot_id' => $bot->id,
            'channel_id' => $bot->channel_id,
            'message_id' => $messageId,
        ]);
    }

    /**
     * Sotilgan e'lonni kanaldan o'chiradi.
     */
    public function deleteElonMessages(MoshinaElon $elon): void
    {
        $sentMessages = $elon->telegramSentMessages()->with('telegramBot')->get();

        foreach ($sentMessages as $sent) {
            $this->deleteMessage($sent->telegramBot->token, $sent->channel_id, $sent->message_id);
            $sent->delete();
        }
    }

    /**
     * Rasmlardan collage yaratadi.
     */
    private function buildCollage($images): ?string
    {
        try {
            $imageDataArray = [];

            foreach ($images as $image) {
                $url = $image->original_url;
                $response = Http::timeout(15)->get($url);

                if ($response->successful()) {
                    $imageDataArray[] = $response->body();
                }
            }

            if (empty($imageDataArray)) {
                return null;
            }

            return $this->collageService->create($imageDataArray);
        } catch (\Throwable $e) {
            Log::error('TelegramChannelService: Collage yaratishda xatolik', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * E'lon captionini formatlaydi.
     */
    public function formatCaption(MoshinaElon $elon): string
    {
        $hashtag = '#' . Str::slug($elon->marka, '_');
        if ($elon->model) {
            $hashtag .= '_' . Str::slug($elon->model, '_');
        }

        $lines = [];
        $lines[] = "♻️ {$hashtag} Сотилади ♻️";
        $lines[] = '';
        $lines[] = "🚗 Модел: {$elon->marka}" . ($elon->model ? " {$elon->model}" : '');

        if ($elon->uzatish_qutisi) {
            $uzatish = $elon->uzatish_qutisi === 'avtomat' ? 'Автомат' : 'Механика';
            $lines[] = "🔧 Узатиш: {$uzatish}";
        }

        if ($elon->rang) {
            $lines[] = "🎨 Ранги: {$elon->rang}";
        }

        if ($elon->kraska_holati) {
            $lines[] = "🖌️ Краскаси: {$elon->kraska_holati}";
        }

        $lines[] = "📆 Йил: {$elon->yil} йил";
        $lines[] = "📏 Пробег: {$elon->formatted_probeg}";

        if ($elon->yoqilgi_turi) {
            $yoqilgi = config("moshina_elon.yoqilgi_turlari.{$elon->yoqilgi_turi}", $elon->yoqilgi_turi);
            $lines[] = "⛽ Ёқилғи: {$yoqilgi}";
        }

        $lines[] = "💰 Нархи: {$elon->formatted_narx}";
        $lines[] = "📞 Тел: +{$elon->telefon}";
        $lines[] = "📍 Манзил: {$elon->shahar}";

        if ($elon->bank_kredit) {
            $lines[] = "🏦 Кредит: Бор";
        }

        $lines[] = '';
        $lines[] = '📲 Элон бериш:';
        $lines[] = '<a href="https://play.google.com/store/apps/details?id=uz.avtovodiy.app">Android</a> | <a href="https://apps.apple.com/app/avto-vodiy/id6744064407">iPhone</a>';
        $lines[] = '';
        $lines[] = '⚠️ Эслатма:';
        $lines[] = 'Машинани кўрмасдан пул ташламанг.';
        $lines[] = 'Канал савдога масъул эмас.';

        return implode("\n", array_filter($lines, fn ($l) => $l !== null));
    }

    /**
     * Telegram kanalga rasm + caption yuboradi.
     */
    private function sendPhoto(string $token, string $channelId, string $photoData, string $caption): ?array
    {
        try {
            $response = Http::timeout(30)
                ->attach('photo', $photoData, 'collage.jpg')
                ->post(self::API_BASE . "{$token}/sendPhoto", [
                    'chat_id' => $channelId,
                    'caption' => $caption,
                    'parse_mode' => 'HTML',
                ]);

            if (! $response->successful()) {
                Log::error('TelegramChannelService: sendPhoto xatosi', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'channel_id' => $channelId,
                ]);
                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('TelegramChannelService: sendPhoto exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Telegram kanaldan xabarni o'chiradi.
     */
    private function deleteMessage(string $token, string $channelId, int $messageId): bool
    {
        try {
            $response = Http::timeout(10)
                ->post(self::API_BASE . "{$token}/deleteMessage", [
                    'chat_id' => $channelId,
                    'message_id' => $messageId,
                ]);

            if (! $response->successful()) {
                Log::warning('TelegramChannelService: deleteMessage xatosi', [
                    'message_id' => $messageId,
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('TelegramChannelService: deleteMessage exception', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
