<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avto Vodiy — Maxfiylik siyosati">
    <meta name="theme-color" content="#060f0b">
    <title>Maxfiylik siyosati — Avto Vodiy</title>
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
        <h1 class="text-3xl lg:text-4xl font-bold mb-2">Maxfiylik siyosati</h1>
        <p class="text-white/40 text-sm mb-10">Oxirgi yangilanish: {{ date('d.m.Y') }}</p>

        <div class="space-y-8 text-white/70 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-white mb-3">1. Kirish</h2>
                <p>Avto Vodiy ilovasidan foydalanganingiz uchun rahmat. Biz sizning shaxsiy ma'lumotlaringiz xavfsizligiga jiddiy yondashamiz. Ushbu Maxfiylik siyosati qanday ma'lumotlar yig'ilishi, saqlanishi va ishlatilishini tushuntiradi.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">2. Yig'iladigan ma'lumotlar</h2>
                <p class="mb-3">Biz quyidagi ma'lumotlarni yig'amiz:</p>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li><strong class="text-white/90">Shaxsiy ma'lumotlar:</strong> ism, telefon raqam, parol (shifrlangan holda)</li>
                    <li><strong class="text-white/90">Profil ma'lumotlari:</strong> profil rasmi, Telegram username (ixtiyoriy)</li>
                    <li><strong class="text-white/90">E'lon ma'lumotlari:</strong> avtomobil haqida ma'lumotlar, rasmlar, narx, shahar, aloqa telefoni</li>
                    <li><strong class="text-white/90">Chat xabarlari:</strong> foydalanuvchilar o'rtasidagi matnli, rasmli va ovozli xabarlar</li>
                    <li><strong class="text-white/90">Qurilma ma'lumotlari:</strong> IP manzil, qurilma turi, operatsion tizim versiyasi</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">3. Ma'lumotlardan foydalanish maqsadlari</h2>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Ilova xizmatlarini ko'rsatish va yaxshilash</li>
                    <li>Foydalanuvchi hisobini yaratish va boshqarish</li>
                    <li>E'lonlarni joylashtirish va ko'rsatish</li>
                    <li>Foydalanuvchilar o'rtasida chat aloqasini ta'minlash</li>
                    <li>Xavfsizlikni ta'minlash va firibgarlikka qarshi kurash</li>
                    <li>OTP orqali telefon raqamni tasdiqlash</li>
                    <li>Texnik muammolarni aniqlash va tuzatish</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">4. Ma'lumotlarni saqlash</h2>
                <p>Sizning ma'lumotlaringiz xavfsiz serverlarda saqlanadi. Parollar shifrlangan (hashed) holda saqlanadi va hech kim tomonidan o'qib bo'lmaydi. Biz ma'lumotlarni faqat xizmat ko'rsatish uchun zarur bo'lgan muddatgacha saqlaymiz.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">5. Ma'lumotlarni uchinchi tomonlarga berish</h2>
                <p class="mb-3">Biz sizning shaxsiy ma'lumotlaringizni uchinchi tomonlarga <strong class="text-white/90">sotmaymiz</strong>. Quyidagi holatlarda ma'lumotlar ulashilishi mumkin:</p>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>SMS xizmatini ko'rsatuvchi provayder (faqat telefon raqam — OTP yuborish uchun)</li>
                    <li>Rasm saqlash xizmati (Cloudflare R2 — faqat rasmlar)</li>
                    <li>Qonun talabiga binoan davlat organlari so'rovi bo'yicha</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">6. Foydalanuvchi huquqlari</h2>
                <p class="mb-3">Siz quyidagi huquqlarga egasiz:</p>
                <ul class="list-disc list-inside space-y-2 ml-2">
                    <li>Shaxsiy ma'lumotlaringizni ko'rish va tahrirlash</li>
                    <li>Hisobingizni o'chirish (barcha ma'lumotlar bilan birga)</li>
                    <li>E'lonlaringizni istalgan vaqtda o'chirish</li>
                    <li>Foydalanuvchilarni bloklash va shikoyat qilish</li>
                    <li>Ma'lumotlaringiz haqida so'rov yuborish</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">7. Bolalar maxfiyligi</h2>
                <p>Avto Vodiy ilovasi 16 yoshdan kichik bolalar uchun mo'ljallanmagan. Biz ataylab 16 yoshdan kichik shaxslardan ma'lumot yig'maymiz.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">8. Cookie va tracking</h2>
                <p>Mobil ilova cookie fayllaridan foydalanmaydi. Autentifikatsiya uchun API token ishlatiladi va u qurilmaning xavfsiz xotirasida saqlanadi.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">9. Xavfsizlik</h2>
                <p>Biz ma'lumotlaringizni himoya qilish uchun zamonaviy xavfsizlik choralarini qo'llaymiz: HTTPS shifrlash, parollarni hashing, token-based autentifikatsiya va ma'lumotlarga ruxsatsiz kirishni oldini olish.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">10. O'zgartirishlar</h2>
                <p>Biz ushbu Maxfiylik siyosatini vaqti-vaqti bilan yangilashimiz mumkin. Muhim o'zgarishlar haqida ilova orqali xabar beramiz.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-white mb-3">11. Bog'lanish</h2>
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
