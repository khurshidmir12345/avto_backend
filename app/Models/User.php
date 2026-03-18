<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
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
        'is_banned',
        'banned_at',
        'ban_reason',
        'telegram_user_id',
        'telegram_username',
        'telegram_first_name',
        'telegram_last_name',
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
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->is_admin;
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

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocked_user_id')
            ->withTimestamps();
    }

    public function blockedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_user_id', 'user_id')
            ->withTimestamps();
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function filedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function telegramChannels(): HasMany
    {
        return $this->hasMany(UserTelegramChannel::class);
    }

    public function activeTelegramChannels(): HasMany
    {
        return $this->hasMany(UserTelegramChannel::class)->where('is_active', true);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function hasBlocked(int $userId): bool
    {
        return $this->blockedUsers()->where('blocked_user_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId): bool
    {
        return $this->blockedByUsers()->where('user_id', $userId)->exists();
    }

    public function getBlockedUserIds(): array
    {
        return $this->blockedUsers()->pluck('blocked_user_id')->toArray();
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
