<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TelegramLinkController extends Controller
{
    /**
     * Web sahifa: /telegram-link?token=xxx
     * Mobil brauzerda → deep linkga redirect
     * Desktop da → "Ilovada oching" ko'rsatish
     */
    public function __invoke(Request $request): View
    {
        $token = $request->query('token', '');
        $webUrl = rtrim(config('telegram.link.web_url', config('app.url')), '/');
        $scheme = config('telegram.link.deep_link_scheme', 'avtovodiy');
        $deepLink = "{$scheme}://telegram-link?token=" . urlencode($token);

        return view('telegram-link', [
            'token' => $token,
            'deepLink' => $deepLink,
            'webUrl' => $webUrl,
        ]);
    }
}
