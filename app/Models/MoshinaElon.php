<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoshinaElon extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'marka',
        'model',
        'yil',
        'probeg',
        'narx',
        'valyuta',
        'rang',
        'yoqilgi_turi',
        'uzatish_qutisi',
        'kraska_holati',
        'shahar',
        'telefon',
        'tavsif',
        'holati',
        'bank_kredit',
        'general',
    ];

    protected function casts(): array
    {
        return [
            'narx' => 'decimal:2',
            'bank_kredit' => 'boolean',
            'general' => 'boolean',
            'yil' => 'integer',
            'probeg' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class, 'car_id')->orderBy('sort_order');
    }
}
