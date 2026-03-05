<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatMediaService
{
    private readonly string $disk;
    private readonly string $pathPrefix;

    public function __construct()
    {
        $this->disk = config('chat.media.disk', 'r2');
        $this->pathPrefix = config('chat.media.path_prefix', 'chat');
    }

    public function uploadImage(UploadedFile $file, int $conversationId): array
    {
        $mimes = config('chat.media.image.mimes', ['jpeg', 'jpg', 'png', 'webp']);
        $maxKb = config('chat.media.image.max_size_kb', 5120);

        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $path = "{$this->pathPrefix}/{$conversationId}/images/" . Str::uuid() . '.' . $ext;

        Storage::disk($this->disk)->put($path, file_get_contents($file->getRealPath()));

        return [
            'path' => $path,
            'disk' => $this->disk,
            'mime' => $file->getMimeType(),
        ];
    }

    public function uploadVoice(UploadedFile $file, int $conversationId): array
    {
        $ext = $file->getClientOriginalExtension() ?: 'm4a';
        $path = "{$this->pathPrefix}/{$conversationId}/voice/" . Str::uuid() . '.' . $ext;

        Storage::disk($this->disk)->put($path, file_get_contents($file->getRealPath()));

        return [
            'path' => $path,
            'disk' => $this->disk,
            'mime' => $file->getMimeType(),
        ];
    }

    public static function imageValidationRules(): array
    {
        $mimes = config('chat.media.image.mimes', ['jpeg', 'jpg', 'png', 'webp']);
        $maxKb = config('chat.media.image.max_size_kb', 5120);

        return [
            'image' => ['required', 'image', 'mimes:' . implode(',', $mimes), 'max:' . $maxKb],
        ];
    }

    public static function voiceValidationRules(): array
    {
        $mimes = ['mp4', 'm4a', 'mp3', 'ogg', 'webm', 'aac'];
        $maxKb = config('chat.media.voice.max_size_kb', 5120);

        return [
            'voice' => ['required', 'file', 'mimes:' . implode(',', $mimes), 'max:' . $maxKb],
        ];
    }
}
