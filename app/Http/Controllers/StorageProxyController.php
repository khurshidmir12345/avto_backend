<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageProxyController extends Controller
{
    /**
     * R2 dan rasmni o'qib, proxy orqali qaytaradi.
     * R2_PUBLIC_URL bo'sh bo'lsa, shu route orqali rasmlar ochiladi.
     */
    public function show(Request $request, string $path): StreamedResponse
    {
        $disk = config('moshina_elon.images.disk', 'r2');

        // Path traversal himoya
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        // Faqat ruxsat etilgan prefikslar
        $allowedPrefixes = [
            config('moshina_elon.images.path_prefix_user', 'uploads'),
            config('moshina_elon.images.path_prefix_elon', 'elonlar'),
            config('moshina_elon.images.path_prefix_avatar', 'avatars'),
        ];

        $allowed = false;
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix . '/')) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            abort(404);
        }

        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        $mimeType = Storage::disk($disk)->mimeType($path) ?: 'image/jpeg';
        $stream = Storage::disk($disk)->readStream($path);

        if (!$stream) {
            abort(500, 'Rasm o\'qishda xatolik');
        }

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
