<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $original = $this->original_url;
        return [
            'id' => (string) $this->id,
            'url' => $original,
            'original' => $original,
            'thumb' => $this->thumb_url,
            'sort_order' => $this->sort_order,
        ];
    }
}
