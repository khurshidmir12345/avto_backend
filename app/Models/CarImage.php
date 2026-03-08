<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CarImage extends Model
{
    protected $fillable = [
        'car_id',
        'user_id',
        'image_key',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(MoshinaElon::class, 'car_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Original rasm URL (CDN domen orqali).
     */
    public function getOriginalUrlAttribute(): string
    {
        return $this->buildUrl($this->image_key);
    }

    /**
     * Thumbnail URL (Cloudflare Image Resizing: ?width=300).
     */
    public function getThumbUrlAttribute(): string
    {
        return $this->buildUrl($this->image_key, ['width' => 300]);
    }

    private function buildUrl(string $key, array $params = []): string
    {
        $base = rtrim(config('moshina_elon.images.cdn_url', config('app.url')), '/');

        // Agar image_key to'liq URL bo'lsa (eski ma'lumotlar) — CDN ga o'giramiz
        if (str_starts_with($key, 'http://') || str_starts_with($key, 'https://')) {
            $parsed = parse_url($key);
            $path = $parsed['path'] ?? '';
            // /media/uploads/1/xxx.jpg -> /uploads/1/xxx.jpg
            $path = preg_replace('#^/media/#', '/', $path);
            $url = $base . $path;
        } else {
            $url = $base . '/' . ltrim($key, '/');
        }

        if (!empty($params)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
        }

        return $url;
    }

    protected static function booted(): void
    {
        static::deleting(function (CarImage $image) {
            try {
                Storage::disk(config('moshina_elon.images.disk', 'r2'))->delete($image->image_key);
            } catch (\Throwable) {
                // Ignore storage errors on delete
            }
        });
    }
}
