<?php

namespace App\Http\Requests\MoshinaElon;

use App\Enums\ElonStatus;
use App\Enums\UzatishQutisi;
use App\Enums\Valyuta;
use App\Enums\YoqilgiTuri;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMoshinaElonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('moshinaElon'));
    }

    public function rules(): array
    {
        $config = config('moshina_elon.validation');
        $yilMax = date('Y') + ($config['yil_max_offset'] ?? 1);

        return [
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'marka' => ['sometimes', 'string', 'max:' . ($config['marka_max'] ?? 100)],
            'model' => ['nullable', 'string', 'max:' . ($config['model_max'] ?? 100)],
            'yil' => ['sometimes', 'integer', 'min:' . ($config['yil_min'] ?? 1990), 'max:' . $yilMax],
            'probeg' => ['sometimes', 'integer', 'min:' . ($config['probeg_min'] ?? 0)],
            'narx' => ['sometimes', 'numeric', 'min:' . ($config['narx_min'] ?? 0)],
            'valyuta' => ['sometimes', 'in:' . implode(',', Valyuta::fromConfig())],
            'rang' => ['nullable', 'string', 'max:' . ($config['rang_max'] ?? 50)],
            'yoqilgi_turi' => ['sometimes', 'in:' . implode(',', YoqilgiTuri::fromConfig())],
            'uzatish_qutisi' => ['sometimes', 'in:' . implode(',', UzatishQutisi::fromConfig())],
            'kraska_holati' => ['nullable', 'string', 'max:' . ($config['kraska_holati_max'] ?? 255)],
            'shahar' => ['sometimes', 'string', 'max:' . ($config['shahar_max'] ?? 100)],
            'telefon' => ['sometimes', 'string', 'regex:' . ($config['telefon_regex'] ?? '/^998[0-9]{9}$/')],
            'tavsif' => ['nullable', 'string', 'max:' . ($config['tavsif_max'] ?? 5000)],
            'holati' => ['sometimes', 'in:' . implode(',', ElonStatus::fromConfig())],
            'bank_kredit' => ['nullable', 'boolean'],
            'general' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'yil.min' => 'Yil ' . config('moshina_elon.validation.yil_min', 1990) . ' dan kam bo\'lmasligi kerak',
            'valyuta.in' => 'Valyuta faqat USD yoki UZS bo\'lishi mumkin',
            'yoqilgi_turi.in' => 'Yoqilg\'i turi noto\'g\'ri',
            'uzatish_qutisi.in' => 'Uzatish qutisi faqat mexanika yoki avtomat',
            'holati.in' => 'Holati faqat active, sold yoki inactive',
            'telefon.regex' => 'Telefon raqam formati: 998XXXXXXXXX',
        ];
    }
}
