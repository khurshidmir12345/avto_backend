<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['avatar_url', 'avatar_icon'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_verified_at',
        'balance',
        'welcome_bonus_received',
        'avatar_path',
        'avatar_disk',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'avatar_path',
        'avatar_disk',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'integer',
            'welcome_bonus_received' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class, 'phone', 'phone');
    }

    public function balanceHistory(): HasMany
    {
        return $this->hasMany(UserBalanceHistory::class);
    }

    public function moshinaElons(): HasMany
    {
        return $this->hasMany(MoshinaElon::class);
    }

    public function carImages(): HasMany
    {
        return $this->hasMany(CarImage::class);
    }

    public function conversationsAsA(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_a_id');
    }

    public function conversationsAsB(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_b_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
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

    public function getAvatarIconAttribute(): ?string
    {
        return $this->getAvatarUrlAttribute();
    }
}
