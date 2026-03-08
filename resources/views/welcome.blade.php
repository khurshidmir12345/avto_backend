<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avto Vodiy — O'zbekistondagi eng qulay avtomobil e'lonlari platformasi. Mashina sotish va sotib olish endi oson.">

    <title>{{ config('app.name', 'Avto Vodiy') }} — Avtomobil e'lonlari</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    {{-- Landing page uchun Tailwind CDN (Vite build yangi klasslarni o'z ichiga olmaydi) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #0a1f18 0%, #1a4d3e 40%, #0f2d25 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3); }
        .glow { box-shadow: 0 0 60px -15px rgba(255,255,255,0.1); }
    </style>
</head>
<body class="bg-[#0a1f18] text-white antialiased min-h-screen">
    {{-- Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#0a1f18]/95 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="/" class="flex items-center gap-3">
                    @if(file_exists(public_path('images/avto-vodiy-logo.png')))
                        <img src="{{ asset('images/avto-vodiy-logo.png') }}" alt="Avto Vodiy" class="h-12 w-auto">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-white flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#1a4d3e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Avto Vodiy</span>
                    @endif
                </a>

                <nav class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-white/80 hover:text-white transition">Imkoniyatlar</a>
                    <a href="#social" class="text-white/80 hover:text-white transition">Ijtimoiy tarmoqlar</a>
                    <a href="#download" class="text-white/80 hover:text-white transition">Yuklab olish</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="#download" class="px-5 py-2.5 rounded-xl bg-white text-[#0a1f18] text-sm font-semibold hover:bg-white/90 transition">
                        Ilovani yuklash
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <main class="pt-16 lg:pt-20">
        <section class="relative overflow-hidden gradient-bg">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-20 right-10 w-96 h-96 bg-white/5 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-20 left-10 w-72 h-72 bg-white/5 rounded-full blur-[100px]"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="text-center max-w-3xl mx-auto">
                    @if(file_exists(public_path('images/avto-vodiy-logo.png')))
                        <img src="{{ asset('images/avto-vodiy-logo.png') }}" alt="Avto Vodiy" class="h-24 sm:h-28 w-auto mx-auto mb-8">
                    @endif
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Avtomobil e'lonlari
                        <span class="text-white">bitta platformada</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-white/80 mb-10 leading-relaxed">
                        Avto Vodiy — O'zbekistondagi eng qulay avtomobil e'lonlari ilovasi. 
                        Mashina sotish, sotib olish va aloqada qolish endi oson.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#download" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white text-[#0a1f18] font-semibold text-lg transition glow card-hover">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-1.18 1.62-2.54 2.57-4.45 2.95-.99.22-1.87.38-2.98.47zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.42.17 1.91-1.5 3.84-3.74 4.42z"/>
                            </svg>
                            App Store
                        </a>
                        <a href="#download" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-lg border border-white/10 transition card-hover">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.9 20.16,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            Google Play
                        </a>
                    </div>
                </div>

                {{-- Hero cards --}}
                <div class="mt-16 lg:mt-24 flex justify-center">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 w-full max-w-2xl">
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 card-hover">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">E'lonlar</p>
                            <p class="text-xs text-white/60">Mashina e'lonlari</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 card-hover">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Chat</p>
                            <p class="text-xs text-white/60">To'g'ridan-to'g'ri aloqa</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 card-hover">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Kategoriyalar</p>
                            <p class="text-xs text-white/60">Yengil, yuk, moto</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 card-hover">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Xavfsiz</p>
                            <p class="text-xs text-white/60">OTP tasdiqlash</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="py-20 lg:py-28 border-t border-white/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Nima uchun Avto Vodiy?</h2>
                    <p class="text-white/80 text-lg max-w-2xl mx-auto">Zamonaviy va qulay platforma orqali avtomobil bozorida faol bo'ling</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 card-hover">
                        <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Rasmlar bilan e'lon</h3>
                        <p class="text-white/70">Har bir e'lon uchun ko'p rasmlar qo'shing. Potentsial xaridorlar mashinangizni batafsil ko'rishi mumkin.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 card-hover">
                        <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Ichki chat</h3>
                        <p class="text-white/70">Sotuvchi bilan to'g'ridan-to'g'ri chat orqali muloqot qiling. Tez va qulay aloqa.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 card-hover">
                        <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Xavfsiz kirish</h3>
                        <p class="text-white/70">OTP orqali telefon raqamingizni tasdiqlang. Shaxsiy ma'lumotlaringiz himoyalangan.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Social / Ijtimoiy tarmoqlar --}}
        <section id="social" class="py-20 lg:py-28 border-t border-white/10 bg-white/5">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Biz bilan bog'laning</h2>
                <p class="text-white/80 text-lg mb-8">Yangiliklar, maslahatlar va qo'llab-quvvatlash uchun ijtimoiy tarmoqlarimizda kuzatib boring</p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-10">
                    <a href="https://t.me/avto_vodiyuz" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-white text-[#0a1f18] font-semibold text-lg transition card-hover w-full sm:w-auto max-w-xs">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                        <span>Telegram kanal</span>
                    </a>
                    <a href="https://instagram.com/avto_ellon" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-white text-[#0a1f18] font-semibold text-lg transition card-hover w-full sm:w-auto max-w-xs">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <span>Instagram</span>
                    </a>
                </div>
                <p class="text-white/70 text-sm">
                    Loyiha egasi:
                    <a href="https://t.me/khurshid_mirzajonov" target="_blank" rel="noopener noreferrer" class="text-white font-medium hover:underline">@khurshid_mirzajonov</a>
                    <span class="text-white/50 mx-2">•</span>
                    <a href="https://instagram.com/khurshid_mirzajonov" target="_blank" rel="noopener noreferrer" class="text-white font-medium hover:underline">@khurshid_mirzajonov</a>
                </p>
            </div>
        </section>

        {{-- CTA / Download --}}
        <section id="download" class="py-20 lg:py-28 border-t border-white/10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Ilovani yuklab oling</h2>
                <p class="text-white/80 text-lg mb-10">Avto Vodiy — avtomobil e'lonlari endi qo'lingizda</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-white text-[#0a1f18] font-semibold text-lg transition card-hover">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c1.33-1.08 2.22-2.59 2.22-4.09 0-.05-.01-.1-.01-.15h-2.09c.01.05.01.1.01.15 0 1.18-.91 2.27-2.13 3.24z"/>
                        </svg>
                        <span>App Store da tez orada</span>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-lg border border-white/20 transition card-hover">
                        <svg class="w-8 h-8" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.9 20.16,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                        </svg>
                        <span>Google Play da tez orada</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-12 border-t border-white/10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        @if(file_exists(public_path('images/avto-vodiy-logo.png')))
                            <img src="{{ asset('images/avto-vodiy-logo.png') }}" alt="Avto Vodiy" class="h-10 w-auto">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#1a4d3e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                        @endif
                        <span class="font-semibold text-white">Avto Vodiy</span>
                    </div>
                    <div class="flex items-center gap-8 text-white/70 text-sm">
                        <a href="https://t.me/avto_vodiyuz" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">Telegram</a>
                        <a href="https://instagram.com/avto_ellon" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">Instagram</a>
                        <a href="https://t.me/khurshid_mirzajonov" target="_blank" rel="noopener noreferrer" class="hover:text-white transition">@khurshid_mirzajonov</a>
                    </div>
                </div>
                <p class="mt-8 text-center text-white/50 text-sm">© {{ date('Y') }} Avto Vodiy. Barcha huquqlar himoyalangan.</p>
            </div>
        </footer>
    </main>
</body>
</html>
