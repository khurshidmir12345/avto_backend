<?php

namespace App\Http\Requests\MoshinaElon;

use Illuminate\Foundation\Http\FormRequest;

class MyElonlarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $perPageMax = config('moshina_elon.per_page_max', 50);

        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:' . $perPageMax],
        ];
    }
}
