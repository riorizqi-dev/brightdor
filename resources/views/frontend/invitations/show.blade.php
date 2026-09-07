<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<!-- Made with the Bang Motion skill v1.0.0 by Bang Tutorial - Cinematic Wedding Web Experience -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $order = $invitation->order;
        $bride = data_get($content, 'couple.bride.name') ?? $order?->bride_name ?? 'Mempelai Wanita';
        $groom = data_get($content, 'couple.groom.name') ?? $order?->groom_name ?? 'Mempelai Pria';
        $brideNick = data_get($content, 'couple.bride.nickname') ?? explode(' ', $bride)[0];
        $groomNick = data_get($content, 'couple.groom.nickname') ?? explode(' ', $groom)[0];
        $weddingDate = $order?->wedding_date;
        $venue = $order?->wedding_venue ?? 'The Grand Pavilion Ballroom';
        $pageTitle = "The Wedding of {$groomNick} & {$brideNick}";
        $primary = data_get($theme, 'primary_color', '#be185d');
        $heroImage = data_get($content, 'hero.image', '/images/invitations/hero.jpg');
        $events = data_get($content, 'events', []);
        if (blank($events) && ($weddingDate || $venue)) {
            $events = [[
                'title' => 'Resepsi Pernikahan',
                'date' => $weddingDate?->translatedFormat('l, d F Y'),
                'time' => '11:00 - 14:00 WIB',
                'venue' => $venue,
                'address' => 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan',
                'map_url' => 'https://maps.google.com/?q=Jakarta',
            ]];
        }
        $gallery = data_get($content, 'gallery', [
            '/images/invitations/gallery_1.jpg',
            '/images/invitations/gallery_2.jpg',
            '/images/invitations/gallery_3.jpg',
            '/images/invitations/gallery_4.jpg',
            '/images/invitations/gallery_5.jpg',
            '/images/invitations/gallery_6.jpg',
        ]);
        $targetDateIso = $weddingDate ? $weddingDate->format('Y-m-d\TH:i:s') : now()->addMonths(2)->format('Y-m-d\TH:i:s');
    @endphp

    <title>{{ $pageTitle }}</title>

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="Kami mengundang Anda untuk hadir dan memberikan doa restu pada hari istimewa pernikahan kami.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($heroImage) }}">
    <meta name="description" content="Undangan pernikahan digital sinematik {{ $groom }} & {{ $bride }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- GSAP for Bang Motion cinematic animation -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --accent: #be185d;
            --accent-lit: #fb7185;
            --accent-glow: rgba(190, 24, 93, 0.35);
            --gold: #d4af37;
            --gold-lit: #fef08a;
            --base: #090a0f;
            --ink: #f8fafc;
            --ink-muted: #94a3b8;
        }

        * { box-sizing: border-box; }

        .font-serif-luxury {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        .font-sans-clean {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        /* SVG Filters container */
        #motionFilters {
            position: fixed;
            width: 0;
            height: 0;
            opacity: 0;
            pointer-events: none;
        }

        /* Bang Motion Cinematic Light Leak */
        .cinematic-leak {
            position: fixed;
            left: 50%;
            top: 50%;
            width: 2200px;
            height: 2200px;
            margin: -1100px 0 0 -1100px;
            border-radius: 50%;
            background: radial-gradient(circle,
                rgba(251, 113, 133, 0.85) 0%,
                rgba(190, 24, 93, 0.45) 30%,
                rgba(212, 175, 55, 0.15) 55%,
                transparent 72%
            );
            filter: blur(80px);
            mix-blend-mode: screen;
            opacity: 0;
            pointer-events: none;
            z-index: 100000;
        }

        /* Bang Motion Ambient floating dust particles */
        .dust-particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(254, 240, 138, 0.9) 0%, rgba(212, 175, 55, 0) 70%);
            pointer-events: none;
            opacity: 0.6;
        }

        /* Ambient subtle grain overlay */
        .luxury-overlay {
            background: linear-gradient(180deg, rgba(9, 10, 15, 0.45) 0%, rgba(9, 10, 15, 0.82) 100%);
        }

        /* Cover Screen (Envelope) */
        .cover-screen {
            position: fixed !important;
            inset: 0 !important;
            z-index: 99999 !important;
            will-change: transform, opacity;
            transition: transform 1s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.9s cubic-bezier(0.77, 0, 0.175, 1);
        }
        .cover-screen.opened {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        /* Headline Kinetic Words & Chars */
        .wd { display: inline-block; white-space: nowrap; }
        .ch { display: inline-block; will-change: transform, opacity, filter; }

        /* Bang Motion Highlight Pill & Underline */
        .hl { position: relative; display: inline-block; }
        .hl i {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -0.06em;
            height: 0.12em;
            border-radius: 0.06em;
            background: linear-gradient(90deg, var(--gold), var(--gold-lit));
            transform: scaleX(0);
            transform-origin: 0 50%;
        }
        .hl.pill {
            padding: 0.08em 0.35em 0.12em;
            border-radius: 0.35em;
            vertical-align: middle;
        }
        .hl.pill i {
            position: absolute;
            inset: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(95deg, rgba(190, 24, 93, 0.45), rgba(251, 113, 133, 0.35));
            border: 1px solid rgba(251, 113, 133, 0.5);
            transform: scaleX(0);
            transform-origin: 0 50%;
        }
        .hl-t { position: relative; z-index: 1; }

        #heroTitle {
            line-height: 1.15;
        }

        /* Floral Gold Divider Pattern */
        .gold-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .gold-divider::before, .gold-divider::after {
            content: '';
            height: 1px;
            width: 52px;
            background: linear-gradient(90deg, transparent, #d4af37);
        }
        .gold-divider::after {
            background: linear-gradient(90deg, #d4af37, transparent);
        }

        /* Spinning Disc */
        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spinSlow 12s linear infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .cover-screen {
                transition: opacity 0.3s ease !important;
            }
            .cover-screen.opened {
                transform: none !important;
            }
            .cinematic-leak {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#faf7f5] font-sans-clean text-slate-800 antialiased selection:bg-rose-200 selection:text-rose-900 overflow-x-hidden">

    {{-- SVG Filter Defs for Directional Motion Blur (Bang Motion technique §2) --}}
    <svg id="motionFilters"><defs></defs></svg>

    {{-- Fullscreen Cinematic Light Leak for Transitions (Bang Motion technique §5) --}}
    <div id="cinematicLeak" class="cinematic-leak"></div>

    {{-- =========================================================================
         1. CINEMATIC COVER SCREEN (Envelope / Buka Undangan)
         ========================================================================= --}}
    <div id="envelopeCover" class="cover-screen fixed inset-0 z-50 flex items-center justify-center overflow-hidden bg-slate-950 text-white">
        <!-- Parallax Background Image -->
        <img id="coverHeroImg" src="{{ asset($heroImage) }}" alt="Wedding Preview" class="absolute inset-0 h-full w-full object-cover opacity-40 scale-105 filter brightness-75 will-change-transform">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/65 to-slate-900/40"></div>

        <!-- Floating Particles Layer -->
        <div id="particlesOverlay" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

        <!-- Envelope Content Box -->
        <div id="coverBox" class="relative z-10 mx-auto max-w-lg px-6 py-12 text-center will-change-transform">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-white/10 px-4 py-1.5 text-xs font-semibold tracking-widest text-amber-200 backdrop-blur-md">
                <svg class="h-3.5 w-3.5 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                THE SACRED WEDDING
            </div>

            <h1 class="mt-8 font-serif-luxury text-5xl sm:text-6xl lg:text-7xl font-light italic tracking-wide text-rose-100">
                {{ $groomNick }} <span class="font-normal text-amber-300 not-italic">&amp;</span> {{ $brideNick }}
            </h1>

            <div class="gold-divider my-6 text-amber-300">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><circle cx="5" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
            </div>

            @if ($weddingDate)
                <p class="text-sm font-medium tracking-wider text-slate-300 uppercase">
                    {{ $weddingDate->translatedFormat('l, d F Y') }}
                </p>
            @endif

            <div class="mt-8 rounded-2xl border border-white/15 bg-white/5 p-6 backdrop-blur-md shadow-2xl">
                <p class="text-xs font-medium uppercase tracking-widest text-slate-400">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <p class="mt-2 font-serif-luxury text-2xl font-bold tracking-wide text-white">Tamu Undangan Istimewa</p>
                <p class="mt-2 text-xs text-slate-300 leading-relaxed">
                    Tanpa mengurangi rasa hormat, kami bermaksud mengundang Anda untuk hadir dalam momen sakral kami.
                </p>

                <button id="btnOpenInvitation" type="button" class="group relative mt-6 inline-flex w-full items-center justify-center gap-3 overflow-hidden rounded-full bg-gradient-to-r from-rose-600 via-pink-600 to-rose-600 px-7 py-3.5 text-sm font-bold text-white shadow-xl shadow-rose-950/40 transition-all duration-300 hover:shadow-rose-600/40 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Buka Undangan</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         2. FLOATING MUSIC & DOCK NAVIGATION
         ========================================================================= --}}
    <!-- Floating Audio Control -->
    <div class="fixed bottom-6 right-6 z-40">
        <button id="btnAudioToggle" type="button" aria-label="Putar Musik Latar" class="group flex h-13 w-13 items-center justify-center rounded-full border border-rose-200 bg-white/95 p-3 text-rose-600 shadow-xl backdrop-blur-md transition-all duration-300 hover:scale-110 active:scale-95">
            <span id="discIndicator" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                <svg id="musicIconOn" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
                <svg id="musicIconOff" class="hidden h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>
                    <path d="M9 9v4.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4"/>
                </svg>
            </span>
        </button>
    </div>

    <!-- Floating Dock Navigation -->
    <nav id="floatingDock" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 rounded-full border border-rose-200/80 bg-white/90 px-5 py-2 shadow-xl backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none translate-y-4">
        <div class="flex items-center gap-6 text-xs font-semibold text-slate-600">
            <a href="#hero" class="hover:text-rose-600 transition-colors flex flex-col items-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] mt-0.5">Home</span>
            </a>
            <a href="#couple" class="hover:text-rose-600 transition-colors flex flex-col items-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="text-[10px] mt-0.5">Mempelai</span>
            </a>
            <a href="#events" class="hover:text-rose-600 transition-colors flex flex-col items-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] mt-0.5">Acara</span>
            </a>
            <a href="#gallery" class="hover:text-rose-600 transition-colors flex flex-col items-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] mt-0.5">Galeri</span>
            </a>
            <a href="#rsvp" class="hover:text-rose-600 transition-colors flex flex-col items-center text-rose-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px] mt-0.5">RSVP</span>
            </a>
        </div>
    </nav>

    {{-- =========================================================================
         3. HERO SECTION (Bang Motion Breathing Camera World)
         ========================================================================= --}}
    <section id="hero" class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 text-white">
        <!-- Cinematic Camera Background (GSAP Drifts) -->
        <div id="heroCameraBg" class="absolute inset-0 will-change-transform">
            <img src="{{ asset($heroImage) }}" alt="Wedding Couple" class="h-full w-full object-cover opacity-65 scale-105 filter brightness-90">
            <div class="luxury-overlay absolute inset-0"></div>
        </div>

        <div id="heroContent" class="relative z-10 mx-auto max-w-4xl px-6 py-28 text-center will-change-transform">
            <p id="heroKicker" class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-300 drop-shadow-sm">
                {{ data_get($content, 'hero.kicker', 'The Wedding Of') }}
            </p>

            <h1 id="heroTitle" class="mt-6 font-serif-luxury text-6xl sm:text-7xl lg:text-8xl font-light italic tracking-tight text-white drop-shadow-md">
                <span class="wd"><span class="txt">{{ $groom }}</span></span>
                &nbsp;<span class="hl pill"><i id="hlAmpBg"></i><span class="hl-t font-normal text-amber-300 not-italic">&amp;</span></span>&nbsp;
                <span class="wd"><span class="txt">{{ $bride }}</span></span>
            </h1>

            <div class="gold-divider my-6 text-amber-300">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><circle cx="5" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
            </div>

            @if ($weddingDate)
                <p id="heroDate" class="text-base sm:text-lg font-medium tracking-widest text-slate-200">
                    {{ $weddingDate->translatedFormat('l, d F Y') }} &bull; {{ $venue }}
                </p>
            @endif

            {{-- Live Countdown Timer --}}
            <div id="countdownContainer" data-target="{{ $targetDateIso }}" class="mt-12 flex items-center justify-center gap-3 sm:gap-6">
                <div class="flex flex-col items-center rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md sm:min-w-[80px] shadow-lg">
                    <span id="timerDays" class="font-serif-luxury text-3xl sm:text-4xl font-bold text-amber-300">00</span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-300">Hari</span>
                </div>
                <span class="font-serif-luxury text-2xl text-amber-300/60">:</span>
                <div class="flex flex-col items-center rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md sm:min-w-[80px] shadow-lg">
                    <span id="timerHours" class="font-serif-luxury text-3xl sm:text-4xl font-bold text-amber-300">00</span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-300">Jam</span>
                </div>
                <span class="font-serif-luxury text-2xl text-amber-300/60">:</span>
                <div class="flex flex-col items-center rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md sm:min-w-[80px] shadow-lg">
                    <span id="timerMinutes" class="font-serif-luxury text-3xl sm:text-4xl font-bold text-amber-300">00</span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-300">Menit</span>
                </div>
                <span class="font-serif-luxury text-2xl text-amber-300/60">:</span>
                <div class="flex flex-col items-center rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md sm:min-w-[80px] shadow-lg">
                    <span id="timerSeconds" class="font-serif-luxury text-3xl sm:text-4xl font-bold text-amber-300">00</span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-300">Detik</span>
                </div>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-center gap-4">
                <a href="#rsvp" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-8 py-3.5 text-sm font-bold text-white shadow-xl shadow-rose-900/40 transition-all duration-300 hover:bg-rose-500 hover:-translate-y-1">
                    Konfirmasi Kehadiran
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                </a>
                <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode("Pernikahan {$groom} & {$bride}") }}&location={{ urlencode($venue) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:bg-white/20">
                    <svg class="h-4 w-4 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Simpan Tanggal
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================================
         4. QUOTE / INTRO SECTION
         ========================================================================= --}}
    <section class="relative py-24 px-6 bg-gradient-to-b from-[#faf7f5] to-white overflow-hidden">
        <div class="mx-auto max-w-3xl text-center">
            <svg class="mx-auto mb-6 text-rose-300" style="width: 42px; height: 42px;" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
            <blockquote class="font-serif-luxury text-xl sm:text-2xl italic leading-relaxed text-slate-700">
                &ldquo;{{ data_get($content, 'quote.text', 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.') }}&rdquo;
            </blockquote>
            <p class="mt-4 text-xs font-bold uppercase tracking-widest text-rose-700">
                {{ data_get($content, 'quote.source', 'QS. Ar-Rum: 21') }}
            </p>
        </div>
    </section>

    {{-- =========================================================================
         5. COUPLE SECTION (MEMPELAI)
         ========================================================================= --}}
    <section id="couple" class="py-24 px-6 bg-white">
        <div class="mx-auto max-w-5xl text-center">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">The Happy Couple</p>
            <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Mempelai Pengantin</h2>
            <div class="gold-divider my-5 text-amber-500">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <p class="mx-auto max-w-xl text-sm text-slate-500 leading-relaxed">
                Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Dengan penuh rasa syukur kami memperkenalkan:
            </p>

            <div class="mt-14 grid gap-12 sm:grid-cols-2">
                {{-- Groom Card --}}
                <div class="motion-card group relative rounded-3xl border border-rose-100 bg-white p-8 shadow-lg shadow-rose-950/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-rose-200">
                    <div class="relative mx-auto h-56 w-56 overflow-hidden rounded-full ring-4 ring-rose-100 shadow-md">
                        <img src="{{ asset(data_get($content, 'couple.groom.photo', '/images/invitations/groom.jpg')) }}" alt="{{ $groom }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <h3 class="mt-6 font-serif-luxury text-3xl font-semibold text-slate-900">{{ $groom }}</h3>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-rose-700">Mempelai Pria</p>
                    @if (data_get($content, 'couple.groom.parents'))
                        <p class="mt-3 text-sm text-slate-500">
                            Putra dari<br>
                            <span class="font-semibold text-slate-700">{{ data_get($content, 'couple.groom.parents') }}</span>
                        </p>
                    @endif
                    @if (data_get($content, 'couple.groom.instagram'))
                        <a href="https://instagram.com/{{ data_get($content, 'couple.groom.instagram') }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-4 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            &#64;{{ data_get($content, 'couple.groom.instagram') }}
                        </a>
                    @endif
                </div>

                {{-- Bride Card --}}
                <div class="motion-card group relative rounded-3xl border border-rose-100 bg-white p-8 shadow-lg shadow-rose-950/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-rose-200">
                    <div class="relative mx-auto h-56 w-56 overflow-hidden rounded-full ring-4 ring-rose-100 shadow-md">
                        <img src="{{ asset(data_get($content, 'couple.bride.photo', '/images/invitations/bride.jpg')) }}" alt="{{ $bride }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <h3 class="mt-6 font-serif-luxury text-3xl font-semibold text-slate-900">{{ $bride }}</h3>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-rose-700">Mempelai Wanita</p>
                    @if (data_get($content, 'couple.bride.parents'))
                        <p class="mt-3 text-sm text-slate-500">
                            Putri dari<br>
                            <span class="font-semibold text-slate-700">{{ data_get($content, 'couple.bride.parents') }}</span>
                        </p>
                    @endif
                    @if (data_get($content, 'couple.bride.instagram'))
                        <a href="https://instagram.com/{{ data_get($content, 'couple.bride.instagram') }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-4 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            &#64;{{ data_get($content, 'couple.bride.instagram') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================================
         6. LOVE STORY SECTION
         ========================================================================= --}}
    @if (data_get($content, 'story'))
        <section class="py-24 px-6 bg-[#faf7f5]">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">Our Journey</p>
                <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Kisah Cinta Kami</h2>
                <div class="gold-divider my-5 text-amber-500">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div class="motion-card mt-8 rounded-3xl border border-rose-100 bg-white p-8 sm:p-12 shadow-sm text-left leading-relaxed text-slate-600">
                    <p class="whitespace-pre-line text-sm sm:text-base font-normal">
                        {{ data_get($content, 'story') }}
                    </p>
                </div>
            </div>
        </section>
    @endif

    {{-- =========================================================================
         7. EVENTS SECTION (AKAD & RESEPSI)
         ========================================================================= --}}
    <section id="events" class="py-24 px-6 bg-white">
        <div class="mx-auto max-w-5xl text-center">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">Save the Date</p>
            <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Agenda &amp; Lokasi</h2>
            <div class="gold-divider my-5 text-amber-500">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <p class="mx-auto max-w-xl text-sm text-slate-500">
                Merupakan kehormatan bagi kami apabila Anda berkenan hadir pada rangkaian acara kami:
            </p>

            <div class="mt-12 grid gap-8 {{ count($events) > 1 ? 'sm:grid-cols-2' : 'max-w-xl mx-auto' }}">
                @foreach ($events as $event)
                    <div class="motion-card group relative rounded-3xl border border-rose-100 bg-[#faf7f5]/50 p-8 text-center transition-all duration-300 hover:border-rose-300 hover:bg-white hover:shadow-2xl hover:-translate-y-1">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 shadow-sm ring-4 ring-rose-50 transition-transform duration-300 group-hover:scale-110">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <h3 class="mt-6 font-serif-luxury text-2xl font-bold text-slate-900">{{ data_get($event, 'title', 'Acara') }}</h3>

                        <div class="mt-6 space-y-2 border-y border-rose-100/80 py-4 text-sm">
                            @if (data_get($event, 'date'))
                                <p class="font-semibold text-slate-800">{{ data_get($event, 'date') }}</p>
                            @endif
                            @if (data_get($event, 'time'))
                                <p class="text-rose-700 font-medium">{{ data_get($event, 'time') }}</p>
                            @endif
                        </div>

                        <div class="mt-4 space-y-1 text-sm text-slate-600">
                            @if (data_get($event, 'venue'))
                                <p class="font-semibold text-slate-800">{{ data_get($event, 'venue') }}</p>
                            @endif
                            @if (data_get($event, 'address'))
                                <p class="text-xs text-slate-500 leading-relaxed">{{ data_get($event, 'address') }}</p>
                            @endif
                        </div>

                        @if (data_get($event, 'map_url'))
                            <a href="{{ data_get($event, 'map_url') }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 rounded-full border border-rose-300 bg-white px-5 py-2.5 text-xs font-bold text-rose-700 shadow-sm transition-all duration-200 hover:bg-rose-50 hover:border-rose-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Buka Google Maps
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================================================================
         8. PHOTO GALLERY SECTION (With Interactive Lightbox)
         ========================================================================= --}}
    @if (! empty($gallery))
        <section id="gallery" class="py-24 px-6 bg-[#faf7f5]">
            <div class="mx-auto max-w-5xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">Moments of Love</p>
                <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Galeri Foto</h2>
                <div class="gold-divider my-5 text-amber-500">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <p class="mx-auto max-w-xl text-sm text-slate-500">
                    Kumpulan momen manis perjalanan cinta kami yang terabadikan dalam lensa kamera.
                </p>

                <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach ($gallery as $index => $photo)
                        <div class="motion-card group relative aspect-[4/5] cursor-pointer overflow-hidden rounded-2xl bg-slate-100 shadow-md transition-all duration-300 hover:shadow-2xl" onclick="openLightbox('{{ asset($photo) }}')">
                            <img src="{{ asset($photo) }}" alt="Foto Galeri {{ $index + 1 }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 flex items-end justify-center p-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-800 shadow backdrop-blur-sm">
                                    <svg class="h-3.5 w-3.5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                    Lihat Foto
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Lightbox Modal -->
        <div id="lightboxModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/90 p-4 backdrop-blur-md" onclick="closeLightbox()">
            <div class="relative max-h-[90vh] max-w-4xl overflow-hidden rounded-2xl" onclick="event.stopPropagation()">
                <button type="button" class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-black" onclick="closeLightbox()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img id="lightboxImg" src="" alt="Zoom Foto" class="max-h-[85vh] w-auto rounded-2xl object-contain shadow-2xl">
            </div>
        </div>
    @endif

    {{-- =========================================================================
         9. DIGITAL GIFTS / WEDDING WISHES (Kado Digital)
         ========================================================================= --}}
    @if (! empty(data_get($content, 'gifts')))
        <section class="py-24 px-6 bg-white">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">Wedding Gift</p>
                <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Tanda Kasih</h2>
                <div class="gold-divider my-5 text-amber-500">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <p class="mx-auto max-w-xl text-sm text-slate-500 leading-relaxed">
                    Doa restu Anda merupakan karunia terindah bagi kami. Namun jika ingin memberikan tanda kasih secara digital, Anda dapat melalui rekening berikut:
                </p>

                <div class="mt-10 grid gap-6 sm:grid-cols-2">
                    @foreach (data_get($content, 'gifts') as $gift)
                        <div class="motion-card rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50/50 to-pink-50/30 p-6 text-left shadow-sm transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <span class="font-serif-luxury text-xl font-bold text-slate-800">{{ data_get($gift, 'bank') }}</span>
                                <svg class="h-6 w-6 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="mt-6">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor Rekening</p>
                                <p class="font-mono text-xl font-bold text-slate-900">{{ data_get($gift, 'account_number') }}</p>
                                <p class="mt-1 text-xs text-slate-500">a.n. <span class="font-semibold text-slate-700">{{ data_get($gift, 'account_name') }}</span></p>
                            </div>
                            <button type="button" onclick="copyToClipboard('{{ data_get($gift, 'account_number') }}', this)" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white py-2 text-xs font-bold text-rose-700 shadow-sm transition hover:bg-rose-50 active:scale-[0.98]">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Salin No. Rekening</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- =========================================================================
         10. RSVP SECTION
         ========================================================================= --}}
    <section id="rsvp" class="py-24 px-6 bg-[#faf7f5]">
        <div class="mx-auto max-w-3xl">
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-rose-600">RSVP &amp; Wishes</p>
                <h2 class="mt-2 font-serif-luxury text-4xl sm:text-5xl font-semibold text-slate-900">Konfirmasi Kehadiran</h2>
                <div class="gold-divider my-5 text-amber-500">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <p class="mx-auto max-w-xl text-sm text-slate-500">
                    Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu secara langsung.
                </p>
            </div>

            {{-- RSVP Counters --}}
            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="motion-card rounded-2xl border border-emerald-100 bg-white p-5 text-center shadow-sm">
                    <p class="font-serif-luxury text-3xl font-bold text-emerald-600">{{ $invitation->rsvp_yes }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-400">Hadir</p>
                </div>
                <div class="motion-card rounded-2xl border border-amber-100 bg-white p-5 text-center shadow-sm">
                    <p class="font-serif-luxury text-3xl font-bold text-amber-500">{{ $invitation->rsvp_maybe }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-400">Ragu-ragu</p>
                </div>
                <div class="motion-card rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm">
                    <p class="font-serif-luxury text-3xl font-bold text-slate-400">{{ $invitation->rsvp_no }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-400">Berhalangan</p>
                </div>
            </div>

            {{-- RSVP Form --}}
            <div class="motion-card mt-8 rounded-3xl border border-rose-100 bg-white p-6 sm:p-10 shadow-lg shadow-rose-950/5">
                @if (session('rsvp_success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 flex items-center gap-2">
                        <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('rsvp_success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('invitations.rsvp', $invitation->slug) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="guest_name" class="text-xs font-bold uppercase tracking-wider text-slate-700">Nama Lengkap</label>
                        <input id="guest_name" type="text" name="guest_name" value="{{ old('guest_name') }}" required maxlength="255" class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200" placeholder="Contoh: Raden Mas Danu & Partner">
                        @error('guest_name')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Konfirmasi Kehadiran</span>
                        <div class="mt-2.5 grid grid-cols-3 gap-3">
                            @foreach (['yes' => 'Hadir', 'maybe' => 'Ragu', 'no' => 'Tidak Hadir'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="attendance" value="{{ $value }}" class="peer sr-only" {{ old('attendance', 'yes') === $value ? 'checked' : '' }}>
                                    <span class="block rounded-xl border border-slate-200 py-3 text-center text-xs sm:text-sm font-bold text-slate-600 transition-all peer-checked:border-rose-600 peer-checked:bg-rose-600 peer-checked:text-white">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('attendance')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="guest_count" class="text-xs font-bold uppercase tracking-wider text-slate-700">Jumlah Tamu Hadir</label>
                        <select id="guest_count" name="guest_count" class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ (int) old('guest_count', 1) === $i ? 'selected' : '' }}>{{ $i }} Orang</option>
                            @endfor
                        </select>
                        @error('guest_count')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="message" class="text-xs font-bold uppercase tracking-wider text-slate-700">Ucapan &amp; Doa Restu</label>
                        <textarea id="message" name="message" rows="3" maxlength="1000" class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200" placeholder="Tuliskan ucapan dan doa tulus untuk kedua mempelai...">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-rose-900/20 transition-all duration-200 hover:bg-rose-500 active:scale-[0.99]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Konfirmasi Kehadiran
                    </button>
                </form>
            </div>

            {{-- Guest Wishes Stream --}}
            @if ($rsvps->isNotEmpty())
                <div class="mt-14">
                    <h3 class="text-center font-serif-luxury text-2xl font-bold text-slate-900">Untaian Doa &amp; Harapan ({{ $rsvps->count() }})</h3>
                    <div class="mt-6 max-h-[420px] space-y-3.5 overflow-y-auto pr-1.5">
                        @foreach ($rsvps as $rsvp)
                            <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm transition-all hover:border-rose-200">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-100 font-serif-luxury text-sm font-bold text-rose-700">
                                            {{ \Illuminate\Support\Str::substr($rsvp->guest_name, 0, 1) }}
                                        </div>
                                        <p class="text-sm font-bold text-slate-900">{{ $rsvp->guest_name }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $rsvp->attendance === 'yes' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($rsvp->attendance === 'maybe' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-500') }}">
                                        {{ $rsvp->attendance === 'yes' ? 'Hadir' : ($rsvp->attendance === 'maybe' ? 'Ragu' : 'Berhalangan') }}
                                    </span>
                                </div>
                                @if ($rsvp->message)
                                    <p class="mt-3 text-sm leading-relaxed text-slate-600 pl-10">{{ $rsvp->message }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- =========================================================================
         11. FOOTER
         ========================================================================= --}}
    <footer class="border-t border-rose-100 bg-white py-14 text-center">
        <p class="font-serif-luxury text-3xl font-semibold text-slate-900">{{ $groom }} &amp; {{ $bride }}</p>
        <p class="mt-2 text-xs uppercase tracking-widest text-slate-400">Terima kasih atas doa dan restu Anda</p>
        <p class="mt-6 text-xs text-slate-400">
            Dibuat dengan penuh cinta via <a href="{{ url('/') }}" class="font-semibold text-rose-600 hover:underline">BrightDor Wedding Platform</a>
        </p>
    </footer>

    {{-- =========================================================================
         12. JAVASCRIPT: BANG MOTION ENGINE, CINEMATIC AUDIO & CAMERA
         ========================================================================= --}}
    <script>
        // =========================================================
        // A. Bang Motion Directional Blur Helper (SVG feGaussianBlur)
        // =========================================================
        const motionDefs = document.querySelector('#motionFilters defs');
        let filterCounter = 0;

        function createDirectionalBlur() {
            const id = 'mb_' + (filterCounter++);
            const NS = 'http://www.w3.org/2000/svg';
            const f = document.createElementNS(NS, 'filter');
            f.id = id;
            f.setAttribute('x', '-70%');
            f.setAttribute('y', '-70%');
            f.setAttribute('width', '240%');
            f.setAttribute('height', '240%');
            const g = document.createElementNS(NS, 'feGaussianBlur');
            g.setAttribute('stdDeviation', '0 0');
            f.appendChild(g);
            motionDefs.appendChild(f);
            return { url: `url(#${id})`, node: g };
        }

        // =========================================================
        // B. Cinematic Particle Generator for Cover
        // =========================================================
        const particlesOverlay = document.getElementById('particlesOverlay');
        if (particlesOverlay) {
            for (let i = 0; i < 24; i++) {
                const dot = document.createElement('div');
                dot.className = 'dust-particle';
                const size = Math.random() * 4 + 2;
                dot.style.width = size + 'px';
                dot.style.height = size + 'px';
                dot.style.left = Math.random() * 100 + '%';
                dot.style.top = Math.random() * 100 + '%';
                particlesOverlay.appendChild(dot);

                if (window.gsap) {
                    gsap.to(dot, {
                        y: '-=' + (Math.random() * 80 + 40),
                        x: '+=' + (Math.random() * 40 - 20),
                        opacity: Math.random() * 0.7 + 0.2,
                        duration: Math.random() * 6 + 4,
                        repeat: -1,
                        yoyo: true,
                        ease: 'sine.inOut'
                    });
                }
            }
        }

        // =========================================================
        // C. Envelope Open with Bang Motion Sequence
        // =========================================================
        const envelopeCover = document.getElementById('envelopeCover');
        const btnOpenInvitation = document.getElementById('btnOpenInvitation');
        const cinematicLeak = document.getElementById('cinematicLeak');

        if (btnOpenInvitation) {
            btnOpenInvitation.addEventListener('click', () => {
                if (window.gsap) {
                    const tl = gsap.timeline({
                        onComplete: () => {
                            envelopeCover.style.display = 'none';
                        }
                    });

                    // Directional blur filter for envelope exit (Bang Motion Rule 3)
                    const blurObj = createDirectionalBlur();
                    envelopeCover.style.filter = blurObj.url;

                    // 1. Light leak blooms through lens (Bang Motion Rule 5)
                    tl.to(cinematicLeak, {
                        opacity: 0.9,
                        scale: 1.25,
                        duration: 0.5,
                        ease: 'power2.in'
                    });

                    // Upward motion blur increase
                    tl.to(blurObj.node, {
                        attr: { stdDeviation: '0 24' },
                        duration: 0.45,
                        ease: 'power2.in'
                    }, 0.15);

                    // 2. Cover moves up with asymmetric rhythm (Bang Motion Rule 4)
                    tl.to(envelopeCover, {
                        yPercent: -100,
                        duration: 0.9,
                        ease: 'power3.inOut'
                    }, 0.25);

                    // Reset blur filter as cover leaves
                    tl.to(blurObj.node, {
                        attr: { stdDeviation: '0 0' },
                        duration: 0.35,
                        ease: 'power2.out'
                    }, 0.7);

                    // 3. Light leak dissipates revealing hero camera push
                    tl.to(cinematicLeak, {
                        opacity: 0,
                        scale: 1.5,
                        duration: 0.75,
                        ease: 'power3.out'
                    }, 0.7);

                    // 4. Hero entrance camera push-through & text split (Bang Motion Rule 2 & 5)
                    tl.fromTo('#heroCameraBg', 
                        { scale: 1.22 }, 
                        { scale: 1.05, duration: 2.2, ease: 'power3.out' }, 
                        0.4
                    );

                    tl.fromTo('#heroTitle', 
                        { opacity: 0, y: 50, scale: 0.92 }, 
                        { opacity: 1, y: 0, scale: 1, duration: 1.3, ease: 'power4.out' }, 
                        0.55
                    );

                    tl.fromTo('#hlAmpBg', 
                        { scaleX: 0 }, 
                        { scaleX: 1, duration: 0.8, ease: 'power3.out' }, 
                        1.0
                    );
                } else {
                    envelopeCover.classList.add('opened');
                    setTimeout(() => {
                        envelopeCover.style.display = 'none';
                    }, 900);
                }

                // Auto play romantic ambient tone
                playAcousticMusic();
            });
        }

        // =========================================================
        // D. Breathing Camera Effect on Hero
        // =========================================================
        if (window.gsap && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            gsap.to('#heroCameraBg img', {
                scale: 1.12,
                duration: 9,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });

            // Scroll reveal for cards
            const cards = document.querySelectorAll('.motion-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        gsap.fromTo(entry.target, 
                            { opacity: 0, y: 35 }, 
                            { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out' }
                        );
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            cards.forEach(card => observer.observe(card));
        }

        // Floating Dock Visibility on Scroll
        const floatingDock = document.getElementById('floatingDock');
        if (floatingDock) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 320) {
                    floatingDock.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                    floatingDock.classList.add('opacity-100', 'translate-y-0');
                } else {
                    floatingDock.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                    floatingDock.classList.remove('opacity-100', 'translate-y-0');
                }
            }, { passive: true });
        }

        // =========================================================
        // E. Countdown Timer
        // =========================================================
        const countdownEl = document.getElementById('countdownContainer');
        if (countdownEl) {
            const targetDateStr = countdownEl.getAttribute('data-target');
            const targetDate = new Date(targetDateStr).getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const diff = targetDate - now;

                if (diff <= 0) {
                    document.getElementById('timerDays').innerText = '00';
                    document.getElementById('timerHours').innerText = '00';
                    document.getElementById('timerMinutes').innerText = '00';
                    document.getElementById('timerSeconds').innerText = '00';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('timerDays').innerText = String(days).padStart(2, '0');
                document.getElementById('timerHours').innerText = String(hours).padStart(2, '0');
                document.getElementById('timerMinutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('timerSeconds').innerText = String(seconds).padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        // =========================================================
        // F. Lightbox
        // =========================================================
        function openLightbox(imgSrc) {
            const modal = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImg');
            img.src = imgSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // =========================================================
        // G. Copy Account Number
        // =========================================================
        function copyToClipboard(text, btnEl) {
            navigator.clipboard.writeText(text).then(() => {
                const span = btnEl.querySelector('span');
                const originalText = span.innerText;
                span.innerText = 'Tersalin!';
                btnEl.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-700');
                setTimeout(() => {
                    span.innerText = originalText;
                    btnEl.classList.remove('bg-emerald-50', 'border-emerald-300', 'text-emerald-700');
                }, 2000);
            });
        }

        // =========================================================
        // H. Romantic Ambient Synthesizer (Web Audio API)
        // =========================================================
        let audioCtx = null;
        let isPlaying = false;
        let musicTimer = null;

        const btnAudioToggle = document.getElementById('btnAudioToggle');
        const discIndicator = document.getElementById('discIndicator');
        const musicIconOn = document.getElementById('musicIconOn');
        const musicIconOff = document.getElementById('musicIconOff');

        const melody = [
            261.63, 329.63, 392.00, 523.25, // C - E - G - C
            293.66, 369.99, 440.00, 587.33, // D - F# - A - D
            329.63, 392.00, 493.88, 659.25, // E - G - B - E
            349.23, 440.00, 523.25, 698.46  // F - A - C - F
        ];
        let noteIdx = 0;

        function playNote(freq) {
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);

            gain.gain.setValueAtTime(0.01, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.06, audioCtx.currentTime + 0.1);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 1.2);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start();
            osc.stop(audioCtx.currentTime + 1.2);
        }

        function playAcousticMusic() {
            if (isPlaying) return;
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                isPlaying = true;
                discIndicator.classList.add('animate-spin-slow');
                musicIconOn.classList.remove('hidden');
                musicIconOff.classList.add('hidden');

                musicTimer = setInterval(() => {
                    playNote(melody[noteIdx]);
                    noteIdx = (noteIdx + 1) % melody.length;
                }, 750);
            } catch (e) {
                console.warn('Audio context init blocked or not supported', e);
            }
        }

        function pauseAcousticMusic() {
            if (!isPlaying) return;
            isPlaying = false;
            clearInterval(musicTimer);
            discIndicator.classList.remove('animate-spin-slow');
            musicIconOn.classList.add('hidden');
            musicIconOff.classList.remove('hidden');
        }

        btnAudioToggle.addEventListener('click', () => {
            if (isPlaying) {
                pauseAcousticMusic();
            } else {
                playAcousticMusic();
            }
        });
    </script>
</body>
</html>
