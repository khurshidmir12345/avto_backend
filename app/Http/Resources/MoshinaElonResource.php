<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoshinaElonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'avatar_url' => $this->user->avatar_url,
            ]),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'icon' => $this->category->icon,
            ]),
            'marka' => $this->marka,
            'model' => $this->model,
            'yil' => $this->yil,
            'probeg' => $this->probeg,
            'narx' => $this->narx,
            'valyuta' => $this->valyuta,
            'rang' => $this->rang,
            'yoqilgi_turi' => $this->yoqilgi_turi,
            'uzatish_qutisi' => $this->uzatish_qutisi,
            'kraska_holati' => $this->kraska_holati,
            'shahar' => $this->shahar,
            'telefon' => $this->telefon,
            'tavsif' => $this->tavsif,
            'holati' => $this->holati,
            'bank_kredit' => $this->bank_kredit,
            'general' => $this->general,
            'images' => CarImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
