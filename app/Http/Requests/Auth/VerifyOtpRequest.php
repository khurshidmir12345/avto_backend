<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^998[0-9]{9}$/'],
            'code' => ['required', 'string', 'size:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Telefon raqam formati: 998XXXXXXXXX (12 ta raqam)',
            'code.size' => 'OTP kodi 4 ta raqamdan iborat bo\'lishi kerak',
        ];
    }
}
