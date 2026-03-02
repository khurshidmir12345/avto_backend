<?php

namespace App\Http\Requests\MoshinaElon;

use App\Services\MoshinaElonImageService;
use Illuminate\Foundation\Http\FormRequest;

class UploadImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return app(MoshinaElonImageService::class)->getValidationRules(required: true);
    }

    public function messages(): array
    {
        return app(MoshinaElonImageService::class)->getValidationMessages();
    }
}
