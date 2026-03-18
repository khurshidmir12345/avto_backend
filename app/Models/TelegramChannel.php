<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChannel extends Model
{
    protected $fillable = [
        'name',
        'username',
        'description',
        'link',
        'avatar_path',
        'avatar_disk',
        'member_count',
        'is_active',
        'sort_order',
    ];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'member_count' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar_path)) {
            return null;
        }

        $r2PublicUrl = config('filesystems.disks.r2.url') ?? env('R2_PUBLIC_URL');

        if (($this->avatar_disk ?? 'r2') === 'r2' && !empty($r2PublicUrl)) {
            return rtrim($r2PublicUrl, '/') . '/' . ltrim($this->avatar_path, '/');
        }

        if (($this->avatar_disk ?? 'r2') === 'public') {
            return rtrim(config('app.url'), '/') . '/storage/' . ltrim($this->avatar_path, '/');
        }

        return rtrim(config('app.url'), '/') . '/media/' . ltrim($this->avatar_path, '/');
    }
}
