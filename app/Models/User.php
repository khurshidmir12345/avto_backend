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

    protected $fillable = [
        'name',
        'phone',
        'password',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class, 'phone', 'phone');
    }

    public function moshinaElons(): HasMany
    {
        return $this->hasMany(MoshinaElon::class);
    }

    public function moshinaElonImages(): HasMany
    {
        return $this->hasMany(MoshinaElonImage::class);
    }
}
