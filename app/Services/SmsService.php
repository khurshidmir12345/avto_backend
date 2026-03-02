<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SmsService
{
    public function sendOtpCode(string $phone, string $code): void
    {
        if (!config('services.sms.enabled', false)) {
            return;
        }

        $url = (string) config('services.sms.url');

        if ($url === '') {
            throw new RuntimeException('SMS API URL topilmadi. SMS_API_URL ni sozlang.');
        }

        $messageTemplate = (string) config('services.sms.templates.otp', 'AVTO VODIY ilovasiga kirish uchun code: {code}');
        $message = str_replace('{code}', $code, $messageTemplate);

        $payload = [
            'phone' => $phone,
            'message' => $message,
            'from' => (string) config('services.sms.from', '4546'),
        ];

        $token = (string) config('services.sms.token');

        $response = Http::timeout((int) config('services.sms.timeout', 10))
            ->acceptJson()
            ->withToken($token)
            ->post($url, $payload);

        if ($response->failed()) {
            throw new RuntimeException('SMS yuborilmadi: ' . $response->status() . ' ' . $response->body());
        }
    }
}
