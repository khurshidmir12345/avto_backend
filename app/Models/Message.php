<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public const TYPE_TEXT = 'text';
    public const TYPE_IMAGE = 'image';
    public const TYPE_VOICE = 'voice';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'type',
        'media_path',
        'media_disk',
        'media_mime',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'boolean',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getMediaUrlAttribute(): ?string
    {
        if (empty($this->media_path)) {
            return null;
        }

        $disk = $this->media_disk ?? config('chat.media.disk', 'r2');
        $r2Url = config('filesystems.disks.r2.url') ?: env('R2_PUBLIC_URL');

        if ($disk === 'r2' && ! empty($r2Url)) {
            return rtrim($r2Url, '/') . '/' . ltrim($this->media_path, '/');
        }

        return rtrim(config('app.url'), '/') . '/api/chat/media/' . $this->id;
    }
}
