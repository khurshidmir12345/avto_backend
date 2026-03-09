# Telegram profil ulash — Sozlash

## 1. Migration

```bash
php artisan migrate
```

## 2. Admin panelda bot qo'shish

1. **Admin** → **Sozlamalar** → **Telegram botlar**
2. **Yangi qo'shish**:
   - Bot nomi: `Avto Vodiy Profil`
   - Bot turi: **Profil ulash (Telegram)**
   - Token: BotFather dan olingan token

---

## Lokal ishlatish (long polling)

Lokalda webhook ishlamaydi — Telegram serverga to'g'ridan ulanmaydi. **Long polling** ishlating:

```bash
php artisan telegram:poll
```

Bu buyruq:
- Webhook ni o'chiradi
- Botni long polling rejimida ishga tushiradi
- `/start` yozilganda javob beradi

**Lokal uchun .env:**

```env
# ngrok yoki localtunnel orqali ochilgan URL
TELEGRAM_LINK_WEB_URL=https://xxxx.ngrok-free.app
```

> **ngrok:** `ngrok http 8080` — Laravel 8080 portda bo'lsa. Chiqadigan URL ni `TELEGRAM_LINK_WEB_URL` ga yozing.

---

## Production (webhook)

```bash
php artisan telegram:set-webhook
```

Bu buyruq `set_profile_bot` uchun webhook o'rnatadi. URL: `https://avtovodiy.uz/api/telegram/webhook/set_profile_bot`

> **Muhim:** `APP_URL` .env da to'g'ri bo'lishi kerak (masalan `https://avtovodiy.uz`).

## .env sozlamalari

```env
TELEGRAM_LINK_WEB_URL=https://avtovodiy.uz
TELEGRAM_LINK_DEEP_SCHEME=avtovodiy
```

## Flow

1. Foydalanuvchi **Profil** → **Telegram ulash** bo'limida bot linkini ko'radi
2. Botga kiradi, `/start` bosing
3. Bot "Ulash 🔗" tugmasi bilan link beradi: `https://avtovodiy.uz/telegram-link?token=xxx`
4. Mobil da: web sahifa avtomatik `avtovodiy://telegram-link?token=xxx` ga redirect qiladi → ilova ochiladi
5. Ilova tokenni API ga yuboradi → profil ulanadi
