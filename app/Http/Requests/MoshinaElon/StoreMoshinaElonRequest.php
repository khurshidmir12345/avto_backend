<?php

namespace App\Http\Requests\MoshinaElon;

use App\Enums\UzatishQutisi;
use App\Enums\Valyuta;
use App\Enums\YoqilgiTuri;
use Illuminate\Foundation\Http\FormRequest;

class StoreMoshinaElonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $config = config('moshina_elon.validation');
        $yilMax = date('Y') + ($config['yil_max_offset'] ?? 1);

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'marka' => ['required', 'string', 'max:' . ($config['marka_max'] ?? 100)],
            'model' => ['nullable', 'string', 'max:' . ($config['model_max'] ?? 100)],
            'yil' => ['required', 'integer', 'min:' . ($config['yil_min'] ?? 1990), 'max:' . $yilMax],
            'probeg' => ['required', 'integer', 'min:' . ($config['probeg_min'] ?? 0)],
            'narx' => ['required', 'numeric', 'min:' . ($config['narx_min'] ?? 0)],
            'valyuta' => ['required', 'in:' . implode(',', Valyuta::fromConfig())],
            'rang' => ['nullable', 'string', 'max:' . ($config['rang_max'] ?? 50)],
            'yoqilgi_turi' => ['required', 'in:' . implode(',', YoqilgiTuri::fromConfig())],
            'uzatish_qutisi' => ['required', 'in:' . implode(',', UzatishQutisi::fromConfig())],
            'kraska_holati' => ['nullable', 'string', 'max:' . ($config['kraska_holati_max'] ?? 255)],
            'shahar' => ['required', 'string', 'max:' . ($config['shahar_max'] ?? 100)],
            'telefon' => ['required', 'string', 'regex:' . ($config['telefon_regex'] ?? '/^998[0-9]{9}$/')],
            'tavsif' => ['nullable', 'string', 'max:' . ($config['tavsif_max'] ?? 5000)],
            'bank_kredit' => ['nullable', 'boolean'],
            'general' => ['nullable', 'boolean'],
            'image_ids' => ['nullable', 'array'],
            'image_ids.*' => ['integer', 'exists:car_images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategoriya tanlanishi shart',
            'category_id.exists' => 'Kategoriya topilmadi',
            'marka.required' => 'Marka kiritilishi shart',
            'yil.required' => 'Yili kiritilishi shart',
            'yil.min' => 'Yil ' . config('moshina_elon.validation.yil_min', 1990) . ' dan kam bo\'lmasligi kerak',
            'probeg.required' => 'Probeg kiritilishi shart',
            'narx.required' => 'Narx kiritilishi shart',
            'valyuta.in' => 'Valyuta faqat USD yoki UZS bo\'lishi mumkin',
            'yoqilgi_turi.in' => 'Yoqilg\'i turi noto\'g\'ri',
            'uzatish_qutisi.in' => 'Uzatish qutisi faqat mexanika yoki avtomat',
            'shahar.required' => 'Shahar kiritilishi shart',
            'telefon.required' => 'Telefon raqam kiritilishi shart',
            'telefon.regex' => 'Telefon raqam formati: 998XXXXXXXXX',
        ];
    }
}
