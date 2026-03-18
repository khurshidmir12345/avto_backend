<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avto Vodiy — Foydalanish shartlari">
    <meta name="theme-color" content="#060f0b">
    <title>Foydalanish shartlari — Avto Vodiy</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { darkest: '#040a07', dark: '#060f0b', DEFAULT: '#0a1f18', mid: '#1a4d3e', light: '#2d7a62', accent: '#34d399' }
                    }
                }
            }
        }
    </script>
    <style>body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }</style>
</head>
<body class="bg-brand-darkest text-white antialiased min-h-screen">
    <header class="bg-brand-dark/80 backdrop-blur-xl border-b border-white/[0.06]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center gap-3">
            <a href="/" class="text-white/60 hover:text-white transition text-sm">&larr; Bosh sahifa</a>
            <span class="text-white/20">|</span>
            <span class="text-sm font-semibold">Avto Vodiy</span>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 lg:py-16">
        <h1 class="text-3xl lg:text-4xl font-bold mb-2">Foydalanish shartlari</h1>
        <p class="text-white/40 text-sm mb-10">Oxirgi yangilanish: {{ date('d.m.Y') }}</p>

        <div class="space-y-8 text-white/70 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-white mb-3">1. Umumiy qoidalar</h2>
                <p>Avto Vodiy ilovasidan foydalanish orqali siz ushbu shartlarni qabul qilgan bo'lasiz. Agar siz ushbu shartlarga rozi bo'lmasangiz, ilovadan foydalanmasligingizni so'raymiz.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">2. Xizmat tavsifi</h2>
                <p>Avto Vodiy — avtomobil e'lonlarini joylashtirish va ko'rish platformasi. Ilova foydalanuvchilarga avtomobil sotish va sotib olish uchun e'lon berish, boshqa foydalanuvchilar bilan chat orqali aloqa qilish imkoniyatini beradi.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">3. Ro'yxatdan o'tish</h2>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Ro'yxatdan o'tish uchun haqiqiy telefon raqam talab qilinadi</li>
                    <li>Telefon raqam OTP (bir martalik kod) orqali tasdiqlanadi</li>
                    <li>Har bir foydalanuvchi faqat bitta hisob yaratishi mumkin</li>
                    <li>Siz hisobingiz xavfsizligi uchun javobgarsiz</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">4. E'lon joylashtirish qoidalari</h2>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>E'lonlar faqat avtomobillarga oid bo'lishi kerak</li>
                    <li>E'lon ma'lumotlari to'g'ri va haqiqiy bo'lishi shart</li>
                    <li>Firibgarlik maqsadidagi e'lonlar taqiqlanadi</li>
                    <li>Noqonuniy yoki o'g'irlangan avtomobillar e'lonlari taqiqlanadi</li>
                    <li>Noto'g'ri yoki yolg'on rasmlar joylashtirish taqiqlanadi</li>
                    <li>Spam yoki takroriy e'lonlar olib tashlanadi</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">5. Chat qoidalari</h2>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Foydalanuvchilar o'rtasidagi muloqot hurmatli bo'lishi kerak</li>
                    <li>Haqoratli, tahdidli yoki noto'g'ri xabarlar yuborish taqiqlanadi</li>
                    <li>Spam xabarlar yuborish taqiqlanadi</li>
                    <li>Noqonuniy kontent (pornografiya, zo'ravonlik va h.k.) yuborish taqiqlanadi</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">6. Taqiqlangan harakatlar</h2>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Boshqa foydalanuvchilarni aldash yoki firibgarlik qilish</li>
                    <li>Ilovaning xavfsizlik tizimini buzishga urinish</li>
                    <li>Botlar yoki avtomatlashtirilgan vositalar orqali foydalanish</li>
                    <li>Boshqa foydalanuvchilarning shaxsiy ma'lumotlarini yig'ish</li>
                    <li>Ilovani noqonuniy maqsadlarda ishlatish</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">7. Moderatsiya</h2>
                <p class="mb-3">Avto Vodiy jamoasi quyidagi harakatlarni amalga oshirish huquqiga ega:</p>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Qoidalarga zid e'lonlarni o'chirish yoki nofaol qilish</li>
                    <li>Qoidabuzarlarning hisobini bloklash (ban qilish)</li>
                    <li>Shikoyatlarni ko'rib chiqish va tegishli choralar ko'rish</li>
                    <li>Shubhali faoliyatni tekshirish</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">8. Shikoyat va report</h2>
                <p>Agar siz boshqa foydalanuvchi yoki e'lon haqida shikoyat qilmoqchi bo'lsangiz, ilova ichidagi "Shikoyat qilish" tugmasidan foydalaning. Barcha shikoyatlar ko'rib chiqiladi va zarur choralar ko'riladi.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">9. Mas'uliyat cheklovi</h2>
                <p>Avto Vodiy foydalanuvchilar o'rtasidagi bitimlar uchun javobgar emas. Ilova faqat e'lon platformasi bo'lib, sotuvchi va xaridor o'rtasidagi munosabatlar uchun mas'uliyat ikki tomonning o'zida.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">10. Hisobni o'chirish</h2>
                <p>Siz istalgan vaqtda hisobingizni ilova orqali o'chirishingiz mumkin. Hisobni o'chirganda barcha shaxsiy ma'lumotlaringiz, e'lonlaringiz va chat tarixingiz butunlay o'chiriladi.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">11. O'zgartirishlar</h2>
                <p>Biz ushbu shartlarni vaqti-vaqti bilan yangilashimiz mumkin. Muhim o'zgarishlar haqida ilova orqali xabar beramiz. Ilovadan foydalanishni davom ettirsangiz, yangi shartlarni qabul qilgan hisoblanasiz.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">12. Bog'lanish</h2>
                <p>Savollar yoki takliflar bo'lsa, biz bilan bog'laning:</p>
                <ul class="list-disc list-inside space-y-2 ml-2 mt-2">
                    <li>Telegram: <a href="https://t.me/avto_vodiyuz" class="text-brand-accent hover:underline" target="_blank">@avto_vodiyuz</a></li>
                    <li>Instagram: <a href="https://instagram.com/avto_ellon" class="text-brand-accent hover:underline" target="_blank">@avto_ellon</a></li>
                </ul>
            </section>
        </div>
    </main>

    <footer class="border-t border-white/[0.04] py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-white/30 text-xs">&copy; {{ date('Y') }} Avto Vodiy. Barcha huquqlar himoyalangan.</p>
        </div>
    </footer>
</body>
</html>
