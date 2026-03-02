<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ism kiritilishi shart',
            'name.min' => 'Ism kamida 2 ta belgidan iborat bo\'lishi kerak',
            'name.max' => 'Ism 100 ta belgidan oshmasligi kerak',
        ];
    }
}
