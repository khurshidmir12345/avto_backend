<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F6E3B">
    <title>Avto Vodiy — Telegram ulash</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0F6E3B 0%, #2E7D4A 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #fff;
        }
        .card {
            background: rgba(255,255,255,0.95);
            color: #2A2A2A;
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .logo { font-size: 28px; font-weight: bold; color: #0F6E3B; margin-bottom: 8px; }
        .subtitle { color: #6B6B6B; font-size: 14px; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: #0F6E3B;
            color: #fff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin-top: 16px;
            transition: background 0.2s;
        }
        .btn:hover { background: #0d5d32; }
        .hint { font-size: 13px; color: #6B6B6B; margin-top: 20px; line-height: 1.5; }
    </style>
    <script>
        (function() {
            var token = @json($token ?? '');
            var deepLink = @json($deepLink ?? '');
            var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            if (token && isMobile) {
                window.location.href = deepLink;
            }
        })();
    </script>
</head>
<body>
    <div class="card">
        <div class="logo">Avto Vodiy</div>
        <div class="subtitle">Telegram hisobingizni ulash</div>

        @if(!empty($token))
            <p>Profilingizni ilovada ulash uchun pastdagi tugmani bosing.</p>
            <a href="{{ $deepLink }}" class="btn">Ilovada ochish 🔗</a>
            <p class="hint">Agar ilova ochilmasa, Avto Vodiy ilovasini oching va Profil → Telegram ulash bo'limiga o'ting.</p>
        @else
            <p>Token topilmadi. Iltimos, Telegram botda /start bosing va yangi link oling.</p>
        @endif
    </div>
</body>
</html>
