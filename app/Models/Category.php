<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function moshinaElons(): HasMany
    {
        return $this->hasMany(MoshinaElon::class);
    }

    public function moshinaElonsCount(): int
    {
        return $this->moshinaElons()->where('holati', 'active')->count();
    }
}
