<?php

namespace App\Services;

use App\Models\MoshinaElon;
use App\Models\UserTelegramChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserTelegramChannelService
{
    private const API_BASE = 'https://api.telegram.org/bot';

    public function __construct(
        private readonly ImageCollageService $collageService,
    ) {}

    /**
     * E'lonni user ning barcha faol kanallariga yuboradi.
     */
    public function sendElonToUserChannels(MoshinaElon $elon): void
    {
        $elon->loadMissing(['images', 'user']);

        $channels = $elon->user->activeTelegramChannels;

        if ($channels->isEmpty()) {
            return;
        }

        if ($elon->images->isEmpty()) {
            Log::info("UserTelegramChannelService: Elon #{$elon->id} da rasm yo'q");
            return;
        }

        $collageData = $this->buildCollage($elon->images);

        foreach ($channels as $channel) {
            $this->sendToChannel($elon, $channel, $collageData);
        }
    }

    /**
     * Bitta kanalga yuborish.
     */
    private function sendToChannel(
        MoshinaElon $elon,
        UserTelegramChannel $channel,
        ?string $collageData,
    ): void {
        try {
            $caption = $this->formatCaption($elon, $channel);

            if ($collageData) {
                $this->sendPhoto($channel->bot_token, $channel->chat_id, $collageData, $caption);
            } else {
                $this->sendMessage($channel->bot_token, $channel->chat_id, $caption);
            }

            $channel->update([
                'last_error_at' => null,
                'last_error_message' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error("UserTelegramChannelService: Kanal #{$channel->id} ga yuborishda xato", [
                'error' => $e->getMessage(),
            ]);

            $channel->update([
                'last_error_at' => now(),
                'last_error_message' => Str::limit($e->getMessage(), 500),
            ]);
        }
    }

    /**
     * Elon captionini user shabloni bo'yicha formatlaydi.
     */
    public function formatCaption(MoshinaElon $elon, UserTelegramChannel $channel): string
    {
        $template = $channel->getEffectiveTemplate();

        $hashtag = '#' . Str::slug($elon->marka, '_');
        if ($elon->model) {
            $hashtag .= '_' . Str::slug($elon->model, '_');
        }

        $elonLink = config('app.url') . '/elon/' . $elon->id;

        $replacements = [
            '{hashtag}' => $hashtag,
            '{marka}' => $elon->marka ?? '',
            '{model}' => $elon->model ?? '',
            '{yil}' => $elon->yil ?? '',
            '{probeg}' => $elon->formatted_probeg ?? '',
            '{narx}' => $elon->formatted_narx ?? '',
            '{valyuta}' => $elon->valyuta ?? '',
            '{telefon}' => $elon->telefon ?? '',
            '{shahar}' => $elon->shahar ?? '',
            '{rang}' => $elon->rang ?? '',
            '{yoqilgi}' => $elon->yoqilgi_turi ?? '',
            '{uzatish}' => $elon->uzatish_qutisi ?? '',
            '{link}' => $elonLink,
            '{footer}' => $channel->footer_text ?? '',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }

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
            Log::error('UserTelegramChannelService: Collage xatosi', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function sendPhoto(string $token, string $chatId, string $photoData, string $caption): void
    {
        $response = Http::timeout(30)
            ->attach('photo', $photoData, 'collage.jpg')
            ->post(self::API_BASE . "{$token}/sendPhoto", [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML',
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'sendPhoto xatosi: ' . ($response->json('description') ?? $response->body())
            );
        }
    }

    private function sendMessage(string $token, string $chatId, string $text): void
    {
        $response = Http::timeout(10)
            ->post(self::API_BASE . "{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'sendMessage xatosi: ' . ($response->json('description') ?? $response->body())
            );
        }
    }
}
