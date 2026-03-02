# Avto Vodiy — API Documentation

**Base URL:** `http://localhost:8080/api`

Barcha so'rovlar uchun:
```
Accept: application/json
Content-Type: application/json
```

Auth talab qilinadigan endpointlar uchun:
```
Authorization: Bearer {token}
```

---

## 1. Auth (Autentifikatsiya)

### 1.1 Ro'yxatdan o'tish

**Endpoint:** `POST /api/auth/register`  
**Auth:** Yo'q

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| name | string | Ha | Max 255 belgi |
| phone | string | Ha | 998XXXXXXXXX (12 ta raqam) |
| password | string | Ha | Min 8 belgi |
| password_confirmation | string | Ha | password bilan bir xil |

```json
{
  "name": "Ali Valiyev",
  "phone": "998901234567",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

**201 Response (yangi foydalanuvchi):**
```json
{
  "message": "Telefon raqamni tasdiqlash uchun OTP kod yuborildi",
  "phone": "998901234567",
  "is_resend": false
}
```

**200 Response (tasdiqlanmagan foydalanuvchi — OTP qayta yuboriladi):**
```json
{
  "message": "Telefon raqamni tasdiqlash uchun OTP kod yuborildi",
  "phone": "998901234567",
  "is_resend": true
}
```

**409:** `Bu telefon raqam allaqachon tasdiqlangan. Iltimos, login qiling.`  
**500:** `OTP kodni SMS orqali yuborishda xatolik yuz berdi`

---

### 1.2 OTP tasdiqlash

**Endpoint:** `POST /api/auth/verify-otp`  
**Auth:** Yo'q

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| phone | string | Ha | 998XXXXXXXXX |
| code | string | Ha | Aniq 4 ta raqam |

```json
{
  "phone": "998901234567",
  "code": "7459"
}
```

**200 Response:**
```json
{
  "message": "Telefon raqam muvaffaqiyatli tasdiqlandi",
  "token": "1|xxx...",
  "user": {
    "id": 1,
    "name": "Ali Valiyev",
    "phone": "998901234567",
    "phone_verified_at": "2026-02-26T13:27:26.000000Z",
    "created_at": "2026-02-26T13:27:26.000000Z",
    "updated_at": "2026-02-26T13:27:26.000000Z"
  }
}
```

---

### 1.3 Login

**Endpoint:** `POST /api/auth/login`  
**Auth:** Yo'q

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| phone | string | Ha | 998XXXXXXXXX |
| password | string | Ha | - |

```json
{
  "phone": "998901234567",
  "password": "12345678"
}
```

**200 Response:**
```json
{
  "message": "Muvaffaqiyatli kirildi",
  "token": "2|xxx...",
  "user": {
    "id": 1,
    "name": "Ali Valiyev",
    "phone": "998901234567",
    "phone_verified_at": "2026-02-26T13:27:26.000000Z",
    "created_at": "2026-02-26T13:27:26.000000Z",
    "updated_at": "2026-02-26T13:27:26.000000Z"
  }
}
```

**401:** `Telefon raqam yoki parol noto'g'ri`  
**403:** `Telefon raqam tasdiqlanmagan. Iltimos, avval ro'yxatdan o'ting.`

---

### 1.4 Joriy foydalanuvchi

**Endpoint:** `GET /api/auth/user`  
**Auth:** Ha

**200 Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Ali Valiyev",
    "phone": "998901234567",
    "phone_verified_at": "2026-02-26T13:27:26.000000Z",
    "created_at": "2026-02-26T13:27:26.000000Z",
    "updated_at": "2026-02-26T13:27:26.000000Z"
  }
}
```

---

### 1.5 Logout

**Endpoint:** `POST /api/auth/logout`  
**Auth:** Ha

**200 Response:**
```json
{
  "message": "Muvaffaqiyatli chiqildi"
}
```

---

### 1.6 Profilni yangilash

**Endpoint:** `PUT /api/auth/profile`  
**Auth:** Ha

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| name | string | Ha | Min 2, Max 100 belgi |

```json
{
  "name": "Yangi Ism"
}
```

**200 Response:**
```json
{
  "message": "Profil muvaffaqiyatli yangilandi",
  "user": {
    "id": 1,
    "name": "Yangi Ism",
    "phone": "998901234567",
    "phone_verified_at": "2026-02-26T13:27:26.000000Z",
    "created_at": "2026-02-26T13:27:26.000000Z",
    "updated_at": "2026-03-02T15:04:40.000000Z"
  }
}
```

---

### 1.7 Parolni o'zgartirish

**Endpoint:** `PUT /api/auth/password`  
**Auth:** Ha

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| current_password | string | Ha | Joriy parol |
| password | string | Ha | Min 8 belgi |
| password_confirmation | string | Ha | password bilan bir xil |

```json
{
  "current_password": "12345678",
  "password": "yangi_parol123",
  "password_confirmation": "yangi_parol123"
}
```

**200 Response:**
```json
{
  "message": "Parol muvaffaqiyatli o'zgartirildi"
}
```

**422:** `Joriy parol noto'g'ri`

---

### 1.8 Profilni o'chirish

**Endpoint:** `DELETE /api/auth/profile`  
**Auth:** Ha

Foydalanuvchi profilini butunlay o'chiradi. Barcha tokenlar ham o'chiriladi.

**200 Response:**
```json
{
  "message": "Profil muvaffaqiyatli o'chirildi"
}
```

---

## 2. Kategoriyalar

### 2.0 Kategoriyalar ro'yxati

**Endpoint:** `GET /api/categories`  
**Auth:** Yo'q

**200 Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Yengil avtolar",
      "slug": "yengil-avtolar",
      "icon": "car",
      "sort_order": 1,
      "elonlar_count": 5
    },
    {
      "id": 2,
      "name": "Yuk mashinalari",
      "slug": "yuk-mashinalari",
      "icon": "truck",
      "sort_order": 2,
      "elonlar_count": 0
    }
  ]
}
```

---

## 3. E'lonlar (Moshina e'lonlari)

### 3.1 Barcha e'lonlar ro'yxati

**Endpoint:** `GET /api/elonlar`  
**Auth:** Yo'q

#### Query parametrlar

| Parametr | Turi | Tavsif |
|----------|------|--------|
| category_id | number | Kategoriya ID bo'yicha filter |
| marka | string | Marka bo'yicha filter |
| shahar | string | Shahar bo'yicha filter |
| yoqilgi_turi | string | benzin, metan, benzin+metan, dizel, elektr, gibrid |
| narx_min | number | Minimal narx |
| narx_max | number | Maksimal narx |
| yil_min | number | Minimal yil |
| yil_max | number | Maksimal yil |
| per_page | number | Sahifadagi elementlar (default: 15, max: 50) |

**200 Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "id": 1,
        "name": "Ali Valiyev",
        "phone": "998901234567"
      },
      "marka": "Gentra",
      "model": "3-pozitsiya",
      "yil": 2023,
      "probeg": 4942,
      "narx": "11800.00",
      "valyuta": "USD",
      "rang": "oq",
      "yoqilgi_turi": "benzin+metan",
      "uzatish_qutisi": "mexanika",
      "kraska_holati": "toza",
      "shahar": "Buxoro",
      "telefon": "998997139003",
      "tavsif": "ABS yoq, balonlari yangi",
      "holati": "active",
      "bank_kredit": false,
      "general": true,
      "images": [
        {
          "id": 1,
          "url": "https://...",
          "sort_order": 1
        }
      ],
      "created_at": "2026-02-26T13:27:26+00:00",
      "updated_at": "2026-02-26T13:27:26+00:00"
    }
  ],
  "links": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 1,
    ...
  }
}
```

---

### 2.2 Bitta e'lon ko'rish

**Endpoint:** `GET /api/elonlar/{moshinaElon}`  
**Auth:** Yo'q

**200 Response:**
```json
{
  "elon": {
    "id": 1,
    "user": {
      "id": 1,
      "name": "Ali Valiyev",
      "phone": "998901234567"
    },
    "marka": "Gentra",
    "model": "3-pozitsiya",
    "yil": 2023,
    "probeg": 4942,
    "narx": "11800.00",
    "valyuta": "USD",
    "rang": "oq",
    "yoqilgi_turi": "benzin+metan",
    "uzatish_qutisi": "mexanika",
    "kraska_holati": "toza",
    "shahar": "Buxoro",
    "telefon": "998997139003",
    "tavsif": "ABS yoq, balonlari yangi",
    "holati": "active",
    "bank_kredit": false,
    "general": true,
    "images": [
      {
        "id": 1,
        "url": "https://...",
        "sort_order": 1
      }
    ],
    "created_at": "2026-02-26T13:27:26+00:00",
    "updated_at": "2026-02-26T13:27:26+00:00"
  }
}
```

---

### 3.3 Mening e'lonlarim

**Endpoint:** `GET /api/elonlar/my/list`  
**Auth:** Ha

| Parametr | Turi | Tavsif |
|----------|------|--------|
| per_page | number | Sahifadagi elementlar (default: 15) |

**200 Response:** 2.1 bilan bir xil format (paginated)

---

### 3.4 Rasmlarni avval yuklash

**Endpoint:** `POST /api/elonlar/images/upload`  
**Auth:** Ha

E'londan oldin rasmlarni yuklang. Qaytgan `id` larni keyin e'lon yaratishda `image_ids` ga yuboring.

**Content-Type:** `multipart/form-data`

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| images | array | Ha | Kamida 1 ta rasm |
| images.* | file | Ha | jpeg, jpg, png, webp, max 20MB |

**201 Response:**
```json
{
  "message": "2 ta rasm muvaffaqiyatli yuklandi",
  "images": [
    {
      "id": 1,
      "user_id": 1,
      "moshina_elon_id": null,
      "path": "uploads/1/xxx.jpg",
      "disk": "r2",
      "url": "https://...",
      "sort_order": 1,
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

---

### 3.5 Yuklanmagan rasmni o'chirish

**Endpoint:** `DELETE /api/elonlar/images/{image}`  
**Auth:** Ha — faqat o'z rasmingiz (elon yaratilmagan)

**200 Response:**
```json
{
  "message": "Rasm muvaffaqiyatli o'chirildi"
}
```

**403:** `Bu rasmni o'chirish huquqingiz yo'q`

---

### 3.6 E'lon yaratish

**Endpoint:** `POST /api/elonlar`  
**Auth:** Ha

#### Jarayon

1. `POST /api/elonlar/images/upload` — rasmlarni yuklash
2. Javobdan `id` larni olish
3. `POST /api/elonlar` — e'lon yaratish (`image_ids: [1, 2, 3]` bilan)

#### Request body

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| category_id | integer | Ha | Kategoriya ID (categories jadvalidan) |
| marka | string | Ha | Max 100 belgi |
| model | string | Yo'q | Max 100 belgi |
| yil | integer | Ha | 1990–(joriy yil+1) |
| probeg | integer | Ha | Min 0 (km) |
| narx | number | Ha | Min 0 |
| valyuta | string | Ha | USD, UZS |
| rang | string | Yo'q | Max 50 belgi |
| yoqilgi_turi | string | Ha | benzin, metan, benzin+metan, dizel, elektr, gibrid |
| uzatish_qutisi | string | Ha | mexanika, avtomat |
| kraska_holati | string | Yo'q | Max 255 belgi |
| shahar | string | Ha | Max 100 belgi |
| telefon | string | Ha | 998XXXXXXXXX |
| tavsif | string | Yo'q | Max 5000 belgi |
| bank_kredit | boolean | Yo'q | - |
| general | boolean | Yo'q | - |
| image_ids | array | Yo'q | Avval yuklangan rasm id'lari [1, 2, 3] |

```json
{
  "category_id": 1,
  "marka": "Gentra",
  "model": "3-pozitsiya",
  "yil": 2023,
  "probeg": 4942,
  "narx": 11800,
  "valyuta": "USD",
  "rang": "oq",
  "yoqilgi_turi": "benzin+metan",
  "uzatish_qutisi": "mexanika",
  "kraska_holati": "toza",
  "shahar": "Buxoro",
  "telefon": "998997139003",
  "tavsif": "ABS yoq, balonlari yangi",
  "bank_kredit": false,
  "general": true,
  "image_ids": [1, 2, 3]
}
```

**201 Response:**
```json
{
  "message": "E'lon muvaffaqiyatli yaratildi",
  "elon": {
    "id": 1,
    "user": { "id": 1, "name": "Ali Valiyev", "phone": "998901234567" },
    "marka": "Gentra",
    "model": "3-pozitsiya",
    "yil": 2023,
    "probeg": 4942,
    "narx": "11800.00",
    "valyuta": "USD",
    "rang": "oq",
    "yoqilgi_turi": "benzin+metan",
    "uzatish_qutisi": "mexanika",
    "kraska_holati": "toza",
    "shahar": "Buxoro",
    "telefon": "998997139003",
    "tavsif": "ABS yoq, balonlari yangi",
    "holati": "active",
    "bank_kredit": false,
    "general": true,
    "images": [
      { "id": 1, "url": "https://...", "sort_order": 1 },
      { "id": 2, "url": "https://...", "sort_order": 2 }
    ],
    "created_at": "2026-02-26T13:27:26+00:00",
    "updated_at": "2026-02-26T13:27:26+00:00"
  }
}
```

---

### 3.7 E'lon yangilash

**Endpoint:** `PUT /api/elonlar/{moshinaElon}`  
**Auth:** Ha — faqat e'lon egasi

Barcha maydonlar ixtiyoriy (faqat o'zgartiriladiganlar yuboriladi).

| Maydon | Qo'shimcha | Validatsiya |
|--------|------------|-------------|
| holati | Update uchun | active, sold, inactive |

**200 Response:**
```json
{
  "message": "E'lon muvaffaqiyatli yangilandi",
  "elon": { ... }
}
```

**403:** `Bu e'lonni o'zgartirish huquqingiz yo'q`

---

### 3.8 E'lon o'chirish

**Endpoint:** `DELETE /api/elonlar/{moshinaElon}`  
**Auth:** Ha — faqat e'lon egasi

**200 Response:**
```json
{
  "message": "E'lon muvaffaqiyatli o'chirildi"
}
```

**403:** `Bu e'lonni o'chirish huquqingiz yo'q`

---

### 3.9 E'longa qo'shimcha rasm yuklash

**Endpoint:** `POST /api/elonlar/{moshinaElon}/images`  
**Auth:** Ha — faqat e'lon egasi

**Content-Type:** `multipart/form-data`

| Maydon | Turi | Majburiy | Validatsiya |
|--------|------|----------|-------------|
| images | array | Ha | Kamida 1 ta rasm |
| images.* | file | Ha | jpeg, jpg, png, webp, max 20MB |

**201 Response:**
```json
{
  "message": "2 ta rasm muvaffaqiyatli yuklandi",
  "images": [
    {
      "id": 3,
      "user_id": 1,
      "moshina_elon_id": 1,
      "path": "elonlar/1/xxx.jpg",
      "disk": "r2",
      "url": "https://...",
      "sort_order": 3,
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

**403:** `Bu e'longa rasm yuklash huquqingiz yo'q`

---

### 3.10 E'londan rasm o'chirish

**Endpoint:** `DELETE /api/elonlar/{moshinaElon}/images/{image}`  
**Auth:** Ha — faqat e'lon egasi

**200 Response:**
```json
{
  "message": "Rasm muvaffaqiyatli o'chirildi"
}
```

**403:** `Bu rasmni o'chirish huquqingiz yo'q`  
**404:** `Rasm bu e'longa tegishli emas`

---

## 3. Endpointlar jadvali

### Auth

| Method | Endpoint | Auth | Tavsif |
|--------|----------|------|--------|
| POST | `/api/auth/register` | Yo'q | Ro'yxatdan o'tish |
| POST | `/api/auth/verify-otp` | Yo'q | OTP tasdiqlash |
| POST | `/api/auth/login` | Yo'q | Kirish |
| GET | `/api/auth/user` | Ha | Joriy foydalanuvchi |
| POST | `/api/auth/logout` | Ha | Chiqish |
| PUT | `/api/auth/profile` | Ha | Profilni yangilash (name) |
| PUT | `/api/auth/password` | Ha | Parolni o'zgartirish |
| DELETE | `/api/auth/profile` | Ha | Profilni o'chirish |

### Kategoriyalar

| Method | Endpoint | Auth | Tavsif |
|--------|----------|------|--------|
| GET | `/api/categories` | Yo'q | Kategoriyalar ro'yxati (elonlar soni bilan) |

### E'lonlar

| Method | Endpoint | Auth | Tavsif |
|--------|----------|------|--------|
| GET | `/api/elonlar` | Yo'q | Barcha e'lonlar (filter) |
| GET | `/api/elonlar/my/list` | Ha | Mening e'lonlarim |
| GET | `/api/elonlar/{id}` | Yo'q | Bitta e'lon |
| POST | `/api/elonlar/images/upload` | Ha | Rasmlarni avval yuklash |
| DELETE | `/api/elonlar/images/{imageId}` | Ha | Yuklanmagan rasmni o'chirish |
| POST | `/api/elonlar` | Ha | E'lon yaratish |
| PUT | `/api/elonlar/{id}` | Ha | E'lon yangilash |
| DELETE | `/api/elonlar/{id}` | Ha | E'lon o'chirish |
| POST | `/api/elonlar/{id}/images` | Ha | E'longa rasm qo'shish |
| DELETE | `/api/elonlar/{id}/images/{imageId}` | Ha | E'londan rasm o'chirish |

---

## 4. Ma'lumotlar

### Telefon formati
- `998` + 9 ta raqam
- Misol: `998901234567`

### OTP
- 4 ta raqam (0000–9999)
- 5 daqiqa amal qiladi
- Ro'yxatdan o'tishda tasdiqlash uchun
- SMS shablon: `AVTO VODIY ilovasiga kirish uchun code: {code}`

### Rasm URL formati
- **R2_PUBLIC_URL** sozlangan bo'lsa: to'g'ridan-to'g'ri R2/Cloudflare URL
- **R2_PUBLIC_URL** bo'sh bo'lsa: Laravel proxy — `{APP_URL}/media/uploads/3/xxx.jpg` (masalan: `http://localhost:8080/media/uploads/3/xxx.jpg`)

### E'lon qiymatlari
- **valyuta:** USD, UZS
- **yoqilgi_turi:** benzin, metan, benzin+metan, dizel, elektr, gibrid
- **uzatish_qutisi:** mexanika, avtomat
- **holati:** active, sold, inactive

### HTTP status kodlari
| Kod | Ma'nosi |
|-----|---------|
| 200 | Muvaffaqiyat |
| 201 | Yaratildi |
| 401 | Autentifikatsiya yo'q |
| 403 | Ruxsat yo'q |
| 404 | Topilmadi |
| 422 | Validatsiya xatosi |
| 500 | Server xatosi |

### Xato formati
```json
{
  "message": "Xato xabari",
  "errors": {
    "maydon_nomi": ["Xato tafsiloti"]
  }
}
```
