<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminChatController extends Controller
{
    public function media(Message $message): StreamedResponse
    {
        if (empty($message->media_path)) {
            abort(404, 'Media topilmadi');
        }

        $disk = $message->media_disk ?? config('chat.media.disk', 'r2');

        if (! Storage::disk($disk)->exists($message->media_path)) {
            abort(404, 'Fayl topilmadi');
        }

        $mime = $message->media_mime ?? 'application/octet-stream';
        $filename = basename($message->media_path);

        $stream = Storage::disk($disk)->readStream($message->media_path);
        if ($stream === null) {
            abort(500, 'Fayl o\'qilolmadi');
        }

        return response()->stream(
            function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            },
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }
}
