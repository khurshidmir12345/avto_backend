# Avto Vodiy — Admin Panel

## O'rnatish

1. **Migrationlarni ishga tushiring:**
   ```bash
   php artisan migrate
   ```

2. **Admin va Avto Vodiy foydalanuvchilarini yarating:**
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

## Kirish

- **URL:** `/admin`
- **Email:** `admin@avtovodiy.uz`
- **Parol:** `password`

> ⚠️ Production da parolni o'zgartiring!

## Imkoniyatlar

### 1. CRUD — Barcha modellar
- **Foydalanuvchilar** — User CRUD
- **Moshina e'lonlari** — MoshinaElon CRUD
- **Kategoriyalar** — Category CRUD
- **E'lon rasmlari** — CarImage CRUD
- **OTP kodlar** — OTP kodlarni ko'rish
- **Balans tarixi** — UserBalanceHistory (faqat ko'rish)
- **E'lon narxlari** — ElonPrice CRUD

### 2. User balans
- Har bir user qatorida **Balans** tugmasi
- Summa va izoh kiritib, balansga pul qo'shish
- Avtomatik ravishda `user_balance_history` ga yoziladi

### 3. User parol o'zgartirish
- Har bir user qatorida **Parol** tugmasi
- Yangi parol kiritish (bcrypt bilan)

### 4. Chat
- **Chat** sahifasi — foydalanuvchilar bilan suhbat
- Avto Vodiy sifatida javob berish
- Mobil ilovada chatda Avto Vodiy birinchi chiqadi
- Rasm va ovoz xabarlar ko'rsatiladi

### 5. Avto Vodiy profil
- **Sozlamalar → Avto Vodiy profil** — ism, telefon, profil rasmini tahrirlash

### 6. OTP kodlar
- Barcha OTP kodlarni ko'rish
- Telefon, kod, muddati, ishlatilgan/yoki yo'q

## Sozlamalar

`.env` da:
```
AVTO_VODIY_PHONE=+998000000001
```

## Filament versiyasi

Filament v5 (Laravel 12)
