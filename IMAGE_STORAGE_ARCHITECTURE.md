# Image Storage Architecture

Avtomobil e'lonlari rasmlari uchun optimallashtirilgan storage va delivery tizimi.

---

## 1. Tizim arxitekturasi

```
┌─────────────┐     presigned-url      ┌─────────────┐
│   Flutter   │ ───────────────────►   │   Backend    │
│   Client    │                        │   (Laravel)  │
└──────┬──────┘                        └──────┬──────┘
       │                                        │
       │ 1. POST /api/images/presigned-url       │
       │    ← urls[] (upload_url, image_key)     │
       │                                        │
       │ 2. PUT (to'g'ridan-to'g'ri)             │
       ▼                                        │
┌─────────────┐                                 │
│ Cloudflare  │  ◄─────────────────────────────┘
│     R2      │     Cache-Control header
└──────┬──────┘
       │
       │ 3. GET (CDN orqali)
       ▼
┌─────────────┐
│   img.      │  Custom domain
│ avtovodiy.uz│  + Image Resizing (?width=300)
└─────────────┘
```

**Asosiy prinsiplar:**
- Client to'g'ridan-to'g'ri R2 ga yuklaydi (backend bandwidth tejash)
- URL lar runtime da generatsiya qilinadi (database da saqlanmaydi)
- Thumbnail uchun alohida fayl yo'q — Cloudflare Image Resizing ishlatiladi

---

## 2. R2 folder strukturasi

```
cars/{car_id}/{uuid}.jpg
pending/{user_id}/{uuid}.jpg
```

| Path | Ma'nosi |
|------|---------|
| `cars/10/abc123xyz.jpg` | E'lon ID=10 ga tegishli rasm |
| `pending/5/xyz789abc.jpg` | User ID=5 yuklagan, hali e'longa ulanmagan rasm |

**Eslatma:** `image_key` — R2 dagi to'liq yo'l (masalan: `cars/10/abc123xyz.jpg`).

---

## 3. Database struktura

### `car_images` jadvali

| Ustun | Turi | Tavsif |
|-------|------|--------|
| id | bigint | Primary key |
| car_id | bigint (nullable) | moshina_elons.id (e'lon) |
| user_id | bigint (nullable) | Yuklagan foydalanuvchi |
| image_key | string | R2 yo'li (cars/10/abc.jpg) |
| sort_order | smallint | Tartib |
| created_at | timestamp | |
| updated_at | timestamp | |

**URL lar database da saqlanmaydi.** Ular runtime da quyidagicha hisoblanadi:

```
Original: https://img.avtovodiy.uz/{image_key}
Thumb:    https://img.avtovodiy.uz/{image_key}?width=300
```

---

## 4. API Endpointlar

### 4.1. Presigned URL olish

```
POST /api/images/presigned-url
Authorization: Bearer {token}
```

**Request:**
```json
{
  "car_id": 10,
  "content_types": ["image/jpeg", "image/png", "image/jpeg"]
}
```

| Parametr | Turi | Majburiy | Tavsif |
|----------|------|----------|--------|
| car_id | integer | Yo'q | Mavjud e'lon ID. Bo'sh bo'lsa — orphan upload (pending/) |
| content_types | array | Ha | Har bir rasm uchun MIME (1–10 ta) |

**Response:**
```json
{
  "message": "Presigned URL lar tayyor",
  "urls": [
    {
      "image_key": "cars/10/abc123xyz.jpg",
      "upload_url": "https://..."
    }
  ]
}
```

---

### 4.2. Image key larni saqlash

```
POST /api/images/save
Authorization: Bearer {token}
```

**Request:**
```json
{
  "car_id": 10,
  "image_keys": [
    "cars/10/abc123xyz.jpg",
    "cars/10/def456uvw.jpg"
  ]
}
```

| Parametr | Turi | Majburiy | Tavsif |
|----------|------|----------|--------|
| car_id | integer | Yo'q | E'lon ID. Orphan uchun bo'sh |
| image_keys | array | Ha | Presigned URL dan olingan key lar (1–10 ta) |

**Response:**
```json
{
  "message": "2 ta rasm saqlandi",
  "images": [
    {
      "id": 1,
      "image_key": "cars/10/abc123xyz.jpg",
      "original": "https://img.avtovodiy.uz/cars/10/abc123xyz.jpg",
      "thumb": "https://img.avtovodiy.uz/cars/10/abc123xyz.jpg?width=300",
      "sort_order": 1
    }
  ]
}
```

---

### 4.3. E'lon rasmlarini olish

```
GET /api/elonlar/{id}/images
```

**Response:**
```json
{
  "images": [
    {
      "id": "1",
      "original": "https://img.avtovodiy.uz/cars/10/abc123xyz.jpg",
      "thumb": "https://img.avtovodiy.uz/cars/10/abc123xyz.jpg?width=300",
      "sort_order": 1
    }
  ]
}
```

---

### 4.4. Rasmni o'chirish

```
DELETE /api/elonlar/{id}/images/{imageId}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Rasm muvaffaqiyatli o'chirildi"
}
```

---

### 4.5. Rasm tartibini o'zgartirish

```
PUT /api/elonlar/{id}/images/reorder
Authorization: Bearer {token}
```

**Request:**
```json
{
  "image_ids": [3, 1, 2]
}
```

**Response:**
```json
{
  "message": "Tartib yangilandi"
}
```

---

### 4.6. Orphan rasmni o'chirish

```
DELETE /api/images/{imageId}
Authorization: Bearer {token}
```

E'lon yaratilishidan oldin yuklangan rasmni o'chiradi.

---

## 5. Image upload flow

```
1. Client: POST /api/images/presigned-url
   Body: { car_id?: 10, content_types: ["image/jpeg", ...] }
   → Backend: image_key lar generatsiya qiladi, presigned URL lar qaytaradi

2. Client: Har bir rasm uchun PUT {upload_url}
   Body: rasm binary
   Headers: Content-Type: image/jpeg
   → To'g'ridan-to'g'ri R2 ga yuklanadi

3. Client: POST /api/images/save
   Body: { car_id?: 10, image_keys: ["cars/10/abc.jpg", ...] }
   → Backend: car_images jadvaliga yozadi

4. (E'lon yaratishda) Client: POST /api/elonlar
   Body: { ..., image_ids: [1, 2, 3] }
   → Backend: car_id ni yangilaydi (orphan → elon)
```

---

## 6. Caching strategiyasi

### R2 object metadata

Yuklash paytida presigned URL orqali quyidagi header o'rnatiladi:

```
Cache-Control: public, max-age=31536000
```

(1 yil cache)

### CDN

- Rasmlar `img.avtovodiy.uz` custom domain orqali beriladi
- Cloudflare CDN avtomatik cache qiladi
- `?width=300` — Cloudflare Image Resizing (on-the-fly thumbnail)

---

## 7. Security

| Tekshiruv | Qanday |
|-----------|--------|
| Autentifikatsiya | Barcha yozuv endpointlari `auth:sanctum` |
| Fayl formati | Faqat jpg, jpeg, png |
| Maksimal hajm | 10MB (client tomonida tekshirish tavsiya) |
| image_key validatsiya | Regex: `cars/\d+/` yoki `pending/\d+/` |

---

## 8. Kelajakda kengaytirish

1. **WebP format** — content_types ga `image/webp` qo'shish
2. **Katta rasmlar** — `?width=800` kabi parametrlar
3. **Lazy loading** — thumb URL dan foydalanish, original kerak bo'lganda
4. **R2 lifecycle** — eski orphan rasmlarni avtomatik o'chirish (masalan, 24 soatdan keyin)
5. **Analytics** — CDN log orqali rasm ko'rilishlarini hisoblash

---

## 9. Sozlamalar (.env)

```env
# R2 (Cloudflare)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=auto
AWS_BUCKET=your-bucket
AWS_ENDPOINT=https://xxx.r2.cloudflarestorage.com

# CDN custom domain
IMAGE_CDN_URL=https://img.avtovodiy.uz

# Presigned URL amal qilish muddati (daqiqa)
IMAGE_PRESIGNED_EXPIRY_MINUTES=15
```

---

## 10. Cloudflare sozlash

### 10.1. R2 bucket va Custom domain

1. **Cloudflare Dashboard** → R2 → **Create bucket** → `avto-vodiy-images` (yoki nomingiz)
2. **Settings** → **Public access** → **Allow Access** (R2 public access)
3. **Custom Domains** → **Connect Domain** → `img.avtovodiy.uz`
4. DNS da CNAME yarating: `img.avtovodiy.uz` → R2 bucket URL

### 10.2. Image Resizing

- **Cloudflare Pro** yoki **Enterprise** plan kerak
- **Dashboard** → **Speed** → **Optimization** → **Image Resizing** yoqing
- `?width=300` parametri avtomatik ishlaydi

### 10.3. CORS sozlash

R2 bucket → **Settings** → **CORS Policy**:

```json
[
  {
    "AllowedOrigins": ["https://avto.chefit.uz", "https://your-app.com", "http://localhost:*"],
    "AllowedMethods": ["GET", "PUT", "HEAD"],
    "AllowedHeaders": ["*"],
    "MaxAgeSeconds": 3600
  }
]
```

### 10.4. Migration ishga tushirish

```bash
php artisan migrate
```

MySQL/PostgreSQL ishlab turgan bo'lishi kerak.
