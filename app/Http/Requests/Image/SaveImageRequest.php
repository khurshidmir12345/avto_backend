<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class SaveImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => ['nullable', 'integer', 'exists:moshina_elons,id'],
            'image_keys' => ['required', 'array', 'min:1', 'max:10'],
            'image_keys.*' => ['required', 'string', 'max:255', 'regex:/^(cars\/\d+\/|pending\/\d+\/)[a-zA-Z0-9]+\.(jpg|jpeg|png)$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'image_keys.required' => 'Kamida bitta image_key yuborilishi kerak',
            'image_keys.*.regex' => 'image_key formati noto\'g\'ri',
        ];
    }
}
