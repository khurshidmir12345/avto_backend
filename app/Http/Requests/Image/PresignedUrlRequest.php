<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class PresignedUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => ['nullable', 'integer', 'exists:moshina_elons,id'],
            'content_types' => ['required', 'array', 'min:1', 'max:10'],
            'content_types.*' => ['required', 'string', 'in:image/jpeg,image/jpg,image/png'],
        ];
    }

    public function messages(): array
    {
        return [
            'content_types.required' => 'Content type lar yuborilishi kerak',
            'content_types.*.in' => 'Rasm formati: jpg, jpeg, png',
        ];
    }
}
