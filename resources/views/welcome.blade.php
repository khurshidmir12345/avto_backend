<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Avto Vodiy — O'zbekistondagi eng qulay avtomobil e'lonlari platformasi. Mashina sotish va sotib olish endi oson.">
    <meta name="theme-color" content="#060f0b">

    <title>{{ config('app.name', 'Avto Vodiy') }} — Avtomobil e'lonlari</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            darkest: '#040a07',
                            dark: '#060f0b',
                            DEFAULT: '#0a1f18',
                            mid: '#1a4d3e',
                            light: '#2d7a62',
                            accent: '#34d399',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        .phone-frame {
            background: linear-gradient(160deg, #1e2e28, #0d1a14);
            border-radius: 2.5rem;
            padding: 0.6rem;
            box-shadow:
                0 50px 100px -20px rgba(0,0,0,0.6),
                0 0 0 1px rgba(255,255,255,0.08),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }
        .phone-frame img {
            border-radius: 2rem;
            width: 100%;
            display: block;
        }
        .phone-frame-sm {
            border-radius: 2rem;
            padding: 0.4rem;
        }
        .phone-frame-sm img {
            border-radius: 1.6rem;
        }

        @keyframes hero-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-16px); }
        }
        .animate-hero-float {
            animation: hero-float 5s ease-in-out infinite;
        }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-d1 { transition-delay: 0.1s; }
        .reveal-d2 { transition-delay: 0.2s; }
        .reveal-d3 { transition-delay: 0.3s; }
        .reveal-d4 { transition-delay: 0.4s; }
        .reveal-d5 { transition-delay: 0.5s; }

        .hero-bg {
            background:
                radial-gradient(ellipse 70% 50% at 70% 0%, rgba(45, 122, 98, 0.25), transparent),
                radial-gradient(ellipse 50% 60% at 20% 80%, rgba(26, 77, 62, 0.15), transparent),
                linear-gradient(180deg, #0a1f18 0%, #060f0b 100%);
        }

        .screenshots-track {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .screenshots-track::-webkit-scrollbar { display: none; }

        .glass { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); }
        .glass-hover:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); }

        .card-lift { transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.4);
        }

        .green-glow {
            box-shadow: 0 0 80px -20px rgba(52, 211, 153, 0.2);
        }

        .feature-dot {
            width: 28px; height: 28px; border-radius: 50%;
            background: rgba(52, 211, 153, 0.12);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .cta-gradient {
            background: linear-gradient(135deg, #1a4d3e 0%, #0a1f18 50%, #1a4d3e 100%);
        }
    </style>
</head>
<body class="bg-brand-darkest text-white antialiased overflow-x-hidden">

    {{-- ==================== HEADER ==================== --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-brand-darkest/80 backdrop-blur-2xl border-b border-white/[0.06]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-[72px]">
                <a href="/" class="flex items-center gap-2.5">
                    @if(file_exists(public_path('images/avto-vodiy-logo.png')))
                        <img src="{{ asset('images/avto-vodiy-logo.png') }}" alt="Avto Vodiy" class="h-10 lg:h-11 w-auto">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-brand-accent/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold">Avto Vodiy</span>
                    @endif
                </a>

                <nav class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm text-white/60 hover:text-white transition-colors duration-300">Imkoniyatlar</a>
                    <a href="#screens" class="text-sm text-white/60 hover:text-white transition-colors duration-300">Ekranlar</a>
                    <a href="#social" class="text-sm text-white/60 hover:text-white transition-colors duration-300">Ijtimoiy tarmoqlar</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="#download" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-brand font-semibold text-sm hover:bg-white/90 transition-all duration-300">
                        Yuklab olish
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="md:hidden hidden pb-4 border-t border-white/[0.06] mt-1">
                <nav class="flex flex-col gap-1 pt-3">
                    <a href="#features" class="px-3 py-2.5 rounded-lg text-white/70 hover:text-white hover:bg-white/5 transition text-sm">Imkoniyatlar</a>
                    <a href="#screens" class="px-3 py-2.5 rounded-lg text-white/70 hover:text-white hover:bg-white/5 transition text-sm">Ekranlar</a>
                    <a href="#social" class="px-3 py-2.5 rounded-lg text-white/70 hover:text-white hover:bg-white/5 transition text-sm">Ijtimoiy tarmoqlar</a>
                    <a href="#download" class="mt-2 px-5 py-2.5 rounded-xl bg-white text-brand font-semibold text-sm text-center">Yuklab olish</a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        {{-- ==================== HERO ==================== --}}
        <section class="hero-bg pt-28 lg:pt-36 pb-16 lg:pb-24 relative">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-mid/10 rounded-full blur-[150px]"></div>
                <div class="absolute -bottom-40 -left-20 w-[400px] h-[400px] bg-brand-mid/5 rounded-full blur-[120px]"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                    <div class="text-center lg:text-left">
                        <div class="reveal inline-flex items-center gap-2 px-4 py-2 rounded-full glass text-sm text-brand-accent font-medium mb-6">
                            <span class="w-2 h-2 rounded-full bg-brand-accent animate-pulse"></span>
                            Avtomobil e'lonlari platformasi
                        </div>

                        <h1 class="reveal reveal-d1 text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.1] mb-6">
                            Mashina oldi-sotdi
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-light"> oson va qulay</span>
                        </h1>

                        <p class="reveal reveal-d2 text-lg text-white/60 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                            Avto Vodiy — O'zbekistondagi zamonaviy avtomobil e'lonlari ilovasi.
                            Mashina sotish, sotib olish va sotuvchi bilan bevosita aloqa qilish imkoniyati.
                        </p>

                        <div class="reveal reveal-d3 flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10">
                            <a href="#download" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-white text-brand font-semibold text-[15px] card-lift">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-1.18 1.62-2.54 2.57-4.45 2.95-.99.22-1.87.38-2.98.47zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.42.17 1.91-1.5 3.84-3.74 4.42z"/></svg>
                                App Store
                            </a>
                            <a href="#download" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl glass glass-hover text-white font-semibold text-[15px] card-lift">
                                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.9 20.16,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                                Google Play
                            </a>
                        </div>

                        <div class="reveal reveal-d4 flex items-center gap-6 justify-center lg:justify-start text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-brand-accent/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-white/50">Bepul</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-brand-accent/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <span class="text-white/50">Xavfsiz</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-brand-accent/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <span class="text-white/50">Tezkor</span>
                            </div>
                        </div>
                    </div>

                    <div class="reveal reveal-d2 flex justify-center lg:justify-end">
                        <div class="phone-frame w-[260px] sm:w-[280px] animate-hero-float green-glow">
                            <img src="{{ asset('images/UI-images/home_page.png') }}" alt="Avto Vodiy — Bosh sahifa" loading="eager">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== APP SCREENSHOTS ==================== --}}
        <section id="screens" class="py-20 lg:py-28 relative section-gradient border-t border-white/[0.04]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <h2 class="reveal text-3xl lg:text-4xl font-bold mb-4">Ilova ekranlari</h2>
                    <p class="reveal reveal-d1 text-white/50 text-lg max-w-xl mx-auto">Zamonaviy va intuitiv interfeys — har bir sahifa foydalanuvchi qulayligi uchun yaratilgan</p>
                </div>

                <div class="screenshots-track overflow-x-auto pb-6 -mx-4 px-4 lg:mx-0 lg:px-0">
                    <div class="flex gap-5 lg:gap-6 justify-start lg:justify-center min-w-max lg:min-w-0">
                        @php
                            $screens = [
                                ['img' => 'home_page.png', 'title' => 'Bosh sahifa', 'desc' => 'Asosiy ekran'],
                                ['img' => 'elon_lists.png', 'title' => "E'lonlar", 'desc' => "Barcha e'lonlar"],
                                ['img' => 'create_alon.png', 'title' => "E'lon berish", 'desc' => 'Yangi e\'lon'],
                                ['img' => 'profile.png', 'title' => 'Profil', 'desc' => 'Shaxsiy kabinet'],
                                ['img' => 'resgister.png', 'title' => "Ro'yxatdan o'tish", 'desc' => 'Tez kirish'],
                            ];
                        @endphp

                        @foreach($screens as $i => $screen)
                            <div class="reveal reveal-d{{ $i + 1 }} flex flex-col items-center gap-3 flex-shrink-0">
                                <div class="phone-frame phone-frame-sm w-[180px] sm:w-[200px] card-lift cursor-default">
                                    <img src="{{ asset('images/UI-images/' . $screen['img']) }}" alt="{{ $screen['title'] }}" loading="lazy">
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-white/90">{{ $screen['title'] }}</p>
                                    <p class="text-xs text-white/40">{{ $screen['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== FEATURES ==================== --}}
        <section id="features" class="py-20 lg:py-28 border-t border-white/[0.04]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="reveal text-3xl lg:text-4xl font-bold mb-4">Nima uchun Avto Vodiy?</h2>
                    <p class="reveal reveal-d1 text-white/50 text-lg max-w-2xl mx-auto">Foydalanuvchilar uchun eng qulay va xavfsiz tajriba yaratish — bizning asosiy maqsadimiz</p>
                </div>

                {{-- Feature 1: E'lonlar --}}
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center mb-24 lg:mb-32">
                    <div class="reveal flex justify-center lg:justify-start order-2 lg:order-1">
                        <div class="phone-frame w-[240px] sm:w-[260px] green-glow">
                            <img src="{{ asset('images/UI-images/elon_lists.png') }}" alt="E'lonlar ro'yxati" loading="lazy">
                        </div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <div class="reveal inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-accent/10 text-brand-accent text-xs font-semibold mb-4 uppercase tracking-wider">
                            E'lonlar
                        </div>
                        <h3 class="reveal reveal-d1 text-2xl lg:text-3xl font-bold mb-4">Minglab e'lonlarni ko'ring</h3>
                        <p class="reveal reveal-d2 text-white/50 text-base leading-relaxed mb-6">
                            Barcha avtomobil e'lonlarini qulay va tezkor interfeys orqali ko'ring.
                            Kategoriyalar, narx filtrlari va qidiruv imkoniyati bilan kerakli mashinani osongina toping.
                        </p>
                        <div class="space-y-3">
                            <div class="reveal reveal-d2 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Batafsil rasmlar va ma'lumotlar</span>
                            </div>
                            <div class="reveal reveal-d3 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Qulay kategoriyalar va filtrlar</span>
                            </div>
                            <div class="reveal reveal-d4 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Tezkor qidiruv tizimi</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Feature 2: E'lon yaratish --}}
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center mb-24 lg:mb-32">
                    <div class="order-1">
                        <div class="reveal inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-accent/10 text-brand-accent text-xs font-semibold mb-4 uppercase tracking-wider">
                            E'lon berish
                        </div>
                        <h3 class="reveal reveal-d1 text-2xl lg:text-3xl font-bold mb-4">Bir necha daqiqada e'lon bering</h3>
                        <p class="reveal reveal-d2 text-white/50 text-base leading-relaxed mb-6">
                            Mashinangizni sotmoqchimisiz? Bir necha bosqichda e'lon joylab, minglab potentsial xaridorlarga yetkazing.
                            Rasmlar, narx, texnik ma'lumotlar — hammasi bir joyda.
                        </p>
                        <div class="space-y-3">
                            <div class="reveal reveal-d2 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Ko'p rasmlar yuklash imkoniyati</span>
                            </div>
                            <div class="reveal reveal-d3 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Oson va tezkor forma</span>
                            </div>
                            <div class="reveal reveal-d4 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Darhol nashr qilish</span>
                            </div>
                        </div>
                    </div>
                    <div class="reveal flex justify-center lg:justify-end order-2">
                        <div class="phone-frame w-[240px] sm:w-[260px] green-glow">
                            <img src="{{ asset('images/UI-images/create_alon.png') }}" alt="E'lon yaratish" loading="lazy">
                        </div>
                    </div>
                </div>

                {{-- Feature 3: Profil & Ro'yxatdan o'tish --}}
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal flex justify-center lg:justify-start gap-4 sm:gap-6 order-2 lg:order-1">
                        <div class="phone-frame phone-frame-sm w-[170px] sm:w-[200px] green-glow -rotate-3">
                            <img src="{{ asset('images/UI-images/resgister.png') }}" alt="Ro'yxatdan o'tish" loading="lazy">
                        </div>
                        <div class="phone-frame phone-frame-sm w-[170px] sm:w-[200px] green-glow rotate-3 mt-8">
                            <img src="{{ asset('images/UI-images/profile.png') }}" alt="Profil sahifasi" loading="lazy">
                        </div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <div class="reveal inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-accent/10 text-brand-accent text-xs font-semibold mb-4 uppercase tracking-wider">
                            Profil & Xavfsizlik
                        </div>
                        <h3 class="reveal reveal-d1 text-2xl lg:text-3xl font-bold mb-4">Xavfsiz va qulay profil</h3>
                        <p class="reveal reveal-d2 text-white/50 text-base leading-relaxed mb-6">
                            OTP orqali tez ro'yxatdan o'ting va shaxsiy profilingizni boshqaring.
                            E'lonlaringiz, chat tarixingiz va sozlamalaringiz — barchasi nazorat ostida.
                        </p>
                        <div class="space-y-3">
                            <div class="reveal reveal-d2 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">OTP orqali xavfsiz tasdiqlash</span>
                            </div>
                            <div class="reveal reveal-d3 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">Shaxsiy profil boshqaruvi</span>
                            </div>
                            <div class="reveal reveal-d4 flex items-center gap-3">
                                <div class="feature-dot">
                                    <svg class="w-3.5 h-3.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-white/70 text-sm">E'lonlar va chat tarixini ko'rish</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== STATS ==================== --}}
        <section class="py-16 border-t border-white/[0.04] bg-white/[0.02]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <div class="reveal text-center">
                        <div class="text-3xl lg:text-4xl font-extrabold text-brand-accent mb-1">100%</div>
                        <div class="text-sm text-white/40">Bepul foydalanish</div>
                    </div>
                    <div class="reveal reveal-d1 text-center">
                        <div class="text-3xl lg:text-4xl font-extrabold text-brand-accent mb-1">24/7</div>
                        <div class="text-sm text-white/40">Ishlash vaqti</div>
                    </div>
                    <div class="reveal reveal-d2 text-center">
                        <div class="text-3xl lg:text-4xl font-extrabold text-brand-accent mb-1">OTP</div>
                        <div class="text-sm text-white/40">Xavfsiz kirish</div>
                    </div>
                    <div class="reveal reveal-d3 text-center">
                        <div class="text-3xl lg:text-4xl font-extrabold text-brand-accent mb-1">Chat</div>
                        <div class="text-sm text-white/40">Ichki xabarlar</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== SOCIAL ==================== --}}
        <section id="social" class="py-20 lg:py-28 border-t border-white/[0.04]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="reveal text-3xl lg:text-4xl font-bold mb-4">Biz bilan bog'laning</h2>
                <p class="reveal reveal-d1 text-white/50 text-lg mb-10">Yangiliklar va qo'llab-quvvatlash uchun ijtimoiy tarmoqlarimizda kuzatib boring</p>

                <div class="reveal reveal-d2 flex flex-col sm:flex-row gap-4 justify-center items-center mb-10">
                    <a href="https://t.me/avto_vodiyuz" target="_blank" rel="noopener noreferrer"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl glass glass-hover text-white font-semibold transition-all duration-300 card-lift">
                        <svg class="w-6 h-6 text-[#26A5E4]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                        Telegram kanal
                    </a>
                    <a href="https://instagram.com/avto_ellon" target="_blank" rel="noopener noreferrer"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl glass glass-hover text-white font-semibold transition-all duration-300 card-lift">
                        <svg class="w-6 h-6 text-[#E4405F]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        Instagram
                    </a>
                </div>

                <p class="reveal reveal-d3 text-white/40 text-sm">
                    Loyiha egasi:
                    <a href="https://t.me/khurshid_mirzajonov" target="_blank" rel="noopener noreferrer" class="text-white/70 hover:text-white font-medium transition">@khurshid_mirzajonov</a>
                </p>
            </div>
        </section>

        {{-- ==================== DOWNLOAD CTA ==================== --}}
        <section id="download" class="py-20 lg:py-28 border-t border-white/[0.04] relative overflow-hidden">
            <div class="absolute inset-0 cta-gradient opacity-50"></div>
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-brand-mid/15 rounded-full blur-[150px]"></div>
            </div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="reveal text-3xl lg:text-4xl font-bold mb-4">Hoziroq yuklab oling</h2>
                <p class="reveal reveal-d1 text-white/50 text-lg mb-10 max-w-xl mx-auto">
                    Avto Vodiy ilovasini yuklab oling va avtomobil e'lonlari dunyosiga qo'shiling
                </p>
                <div class="reveal reveal-d2 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-white text-brand font-semibold text-lg transition-all duration-300 card-lift">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-1.18 1.62-2.54 2.57-4.45 2.95-.99.22-1.87.38-2.98.47zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.42.17 1.91-1.5 3.84-3.74 4.42z"/></svg>
                        <div class="text-left">
                            <div class="text-[10px] font-normal text-brand/60 uppercase leading-none mb-0.5">Tez orada</div>
                            <div class="text-base leading-tight">App Store</div>
                        </div>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl glass glass-hover text-white font-semibold text-lg border border-white/10 transition-all duration-300 card-lift">
                        <svg class="w-7 h-7" viewBox="0 0 24 24"><path fill="currentColor" d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.9 20.16,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                        <div class="text-left">
                            <div class="text-[10px] font-normal text-white/40 uppercase leading-none mb-0.5">Tez orada</div>
                            <div class="text-base leading-tight">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="py-10 border-t border-white/[0.04]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2.5">
                    @if(file_exists(public_path('images/avto-vodiy-logo.png')))
                        <img src="{{ asset('images/avto-vodiy-logo.png') }}" alt="Avto Vodiy" class="h-9 w-auto">
                    @else
                        <div class="w-9 h-9 rounded-lg bg-brand-accent/20 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-semibold text-white/90">Avto Vodiy</span>
                    @endif
                </div>

                <div class="flex items-center gap-6 text-sm text-white/40">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors duration-300">Maxfiylik siyosati</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors duration-300">Foydalanish shartlari</a>
                    <a href="https://t.me/avto_vodiyuz" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-300">Telegram</a>
                    <a href="https://instagram.com/avto_ellon" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-300">Instagram</a>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-white/[0.04] text-center">
                <p class="text-white/30 text-xs">&copy; {{ date('Y') }} Avto Vodiy. Barcha huquqlar himoyalangan.</p>
            </div>
        </div>
    </footer>

    {{-- ==================== SCRIPTS ==================== --}}
    <script>
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const isOpen = !mobileMenu.classList.contains('hidden');
            menuBtn.innerHTML = isOpen
                ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>';
        });

        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>';
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Header background on scroll
        const header = document.querySelector('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('shadow-lg', 'shadow-black/20');
            } else {
                header.classList.remove('shadow-lg', 'shadow-black/20');
            }
        }, { passive: true });
    </script>
</body>
</html>
