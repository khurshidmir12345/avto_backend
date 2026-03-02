<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MoshinaElonImage extends Model
{
    protected $appends = ['public_url'];

    protected $fillable = [
        'user_id',
        'moshina_elon_id',
        'path',
        'disk',
        'url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moshinaElon(): BelongsTo
    {
        return $this->belongsTo(MoshinaElon::class);
    }

    /**
     * To'g'ri ishlaydigan URL. R2_PUBLIC_URL bo'sh bo'lsa, Laravel proxy ishlatiladi.
     */
    public function getPublicUrlAttribute(): string
    {
        $r2PublicUrl = config('filesystems.disks.r2.url') ?? env('R2_PUBLIC_URL');

        if (!empty($r2PublicUrl) && $this->url && str_starts_with($this->url, 'http')) {
            return $this->url;
        }

        return rtrim(config('app.url'), '/') . '/media/' . ltrim($this->path ?? '', '/');
    }

    protected static function booted(): void
    {
        static::deleting(function (MoshinaElonImage $image) {
            if ($image->path) {
                try {
                    Storage::disk($image->disk ?? 'r2')->delete($image->path);
                } catch (\Throwable) {
                    // Ignore storage errors on delete
                }
            }
        });
    }
}
