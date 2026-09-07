@extends('frontend.layouts.app')

@section('title', 'Vendor Pernikahan Terlengkap')

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-rose-50/90 via-rose-50/40 to-white pt-6 pb-10 sm:pt-8 sm:pb-14 lg:pt-10 lg:pb-16">
        {{-- Ambient decorative background glows --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-rose-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-20 top-1/2 h-80 w-80 rounded-full bg-rose-100/50 blur-3xl"></div>

        <div class="bd-container relative">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                {{-- Left Column: Text & Search & Trust Indicators --}}
                <div class="lg:col-span-7 text-left">
                    {{-- Kicker badge --}}
                    <div class="hero-enter-1 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-100/80 border border-rose-200 text-rose-700 text-xs font-bold tracking-wide shadow-xs mb-3">
                        <span class="flex h-2 w-2 rounded-full bg-rose-600 animate-pulse"></span>
                        PREMIUM WEDDING MARKETPLACE
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="hero-enter-2 font-display text-4xl sm:text-5xl lg:text-[3.25rem] font-extrabold leading-[1.12] tracking-tight text-ink-900">
                        Wujudkan Pernikahan<br>
                        Impianmu Bersama
                        <span class="text-rose-600">BrightDor</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="hero-enter-3 mt-4 text-base sm:text-lg leading-relaxed text-ink-600 max-w-xl">
                        Temukan {{ $totalVendors }}+ vendor pernikahan terbaik — venue megah, katering istimewa, dekorasi impian, hingga fotografer profesional terpercaya dalam satu kurasi eksklusif.
                    </p>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('vendors.index') }}" class="hero-enter-3 mt-6 max-w-xl">
                        <div class="flex flex-col sm:flex-row items-stretch gap-2 p-1.5 rounded-2xl bg-white border border-rose-200/90 shadow-[0_6px_24px_rgba(198,67,106,0.12)] transition-all focus-within:border-rose-400 focus-within:shadow-[0_8px_30px_rgba(198,67,106,0.18)]">
                            <div class="relative flex-1 flex items-center">
                                <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                                <input name="q" type="search" placeholder="Cari venue, katering, fotografer, kota..."
                                       class="w-full bg-transparent border-0 pl-11 pr-4 py-3 text-sm text-ink-800 placeholder:text-ink-400 focus:outline-none focus:ring-0">
                            </div>
                            <button type="submit" class="bd-btn-primary py-3.5 px-7 text-base font-bold rounded-xl shrink-0 cursor-pointer shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                <span>Cari Vendor</span>
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                            </button>
                        </div>
                    </form>

                    {{-- Trust Indicators --}}
                    <div class="hero-enter-4 mt-6 pt-4 border-t border-rose-200/50 flex flex-wrap items-center gap-6 sm:gap-8">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100/90 text-rose-600 font-bold shadow-xs">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-ink-900 leading-tight">{{ $totalVendors }}+ Vendor</p>
                                <p class="text-xs text-ink-500 font-medium">Terverifikasi Resmi</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-500 shadow-xs ring-1 ring-amber-200/60">
                                <svg class="h-4 w-4 fill-amber-400 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-ink-900 leading-tight">4.9 / 5.0</p>
                                <p class="text-xs text-ink-500 font-medium">Rating Pasangan</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100/90 text-rose-600 font-bold shadow-xs">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-ink-900 leading-tight">100% Aman</p>
                                <p class="text-xs text-ink-500 font-medium">Rekber Resmi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Layered Visual Photo Composition --}}
                <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                    <div class="hero-floating-card relative mx-auto max-w-sm sm:max-w-md lg:max-w-none">
                        {{-- Main Photo Card --}}
                        <div class="overflow-hidden rounded-2xl bg-white p-2.5 shadow-[0_20px_50px_rgba(198,67,106,0.18)] ring-1 ring-black/5">
                            <div class="relative overflow-hidden rounded-xl aspect-[4/5] bg-rose-100">
                                <img src="{{ asset('images/defaults/fotografer.jpg') }}" alt="Momen Pernikahan Bahagia" class="h-full w-full object-cover transition-transform duration-700 hover:scale-105">
                            </div>
                        </div>

                        {{-- Floating Accent Badge Top-Right --}}
                        <div class="hero-float-badge-1 absolute -top-4 -right-2 sm:-right-4 rounded-xl bg-white/95 backdrop-blur-md p-3 shadow-xl border border-rose-100/80 flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-rose-200/60 shadow-xs">
                                <x-frontend.ring-icon class="h-5 w-5 text-rose-600"/>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-ink-900 leading-tight">Momen Abadi</p>
                                <p class="text-[10px] text-ink-400 font-semibold">1,000+ Pasangan Bahagia</p>
                            </div>
                        </div>

                        {{-- Floating Accent Badge Bottom-Left --}}
                        <div class="hero-float-badge-2 absolute -bottom-4 -left-2 sm:-left-4 rounded-xl bg-white/95 backdrop-blur-md p-3 shadow-xl border border-rose-100/80 flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200/60 shadow-xs">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-ink-900 leading-tight">Garansi Kualitas</p>
                                <p class="text-[10px] text-emerald-600 font-bold">Vendor Pilihan Terbaik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section id="kategori" class="bd-section scroll-mt-20 reveal-on-scroll">
        <div class="bd-container">
            <div class="flex items-end justify-between">
                <div>
                    <span class="bd-section-kicker">Kategori</span>
                    <h2 class="bd-section-title mt-2">Cari Berdasarkan Kebutuhanmu</h2>
                </div>
                <a href="{{ route('vendors.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                    Lihat Semua
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($categories as $index => $cat)
                    <a href="{{ route('vendors.category', $cat->slug) }}"
                       class="group bd-category-card">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600 ring-1 ring-rose-200 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:ring-rose-600 group-hover:shadow-lg group-hover:scale-110 group-hover:-translate-y-0.5 group-hover:rotate-1">
                            <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-6 w-6"/>
                        </span>
                        <span class="mt-4 block font-display text-base font-bold text-ink-900 group-hover:text-rose-600 transition-colors">{{ $cat->name }}</span>
                        <span class="mt-1 block text-xs text-ink-400 font-medium">{{ $cat->vendors_count }} vendor</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured vendors --}}
    @if ($featuredVendors->isNotEmpty())
        <section id="unggulan" class="bg-ink-50 border-y border-ink-200/70 scroll-mt-20 reveal-on-scroll">
            <div class="bd-section bd-container">
                <div class="flex items-end justify-between">
                    <div>
                        <span class="bd-section-kicker">Pilihan Editor</span>
                        <h2 class="bd-section-title mt-2">Vendor Unggulan</h2>
                    </div>
                    <a href="{{ route('vendors.index', ['sort' => 'featured']) }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                        Lihat Semua
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredVendors as $index => $vendor)
                        <div class="reveal-on-scroll stagger-{{ ($index % 3) + 1 }}">
                            <x-frontend.vendor-card :vendor="$vendor"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Kenapa Memilih BrightDor? --}}
    <section id="keunggulan" class="scroll-mt-20 py-16 sm:py-20 bg-white border-b border-ink-200/60 reveal-on-scroll">
        <div class="bd-container">
            <div class="text-center max-w-2xl mx-auto">
                <span class="bd-section-kicker">Nilai &amp; Jaminan Layanan</span>
                <h2 class="bd-section-title mt-2">Kenapa Memilih BrightDor?</h2>
                <p class="mt-3 text-base text-ink-600">Platform kurasi vendor pernikahan terpercaya dengan ekosistem pembayaran aman dan perlindungan menyeluruh untuk hari bahagia Anda.</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-rose-100 bg-gradient-to-b from-rose-50/50 to-white p-7 shadow-xs hover:shadow-md transition-all">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 ring-1 ring-rose-200 shadow-xs mb-5">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink-900">Kurasi Vendor Terverifikasi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-600">Setiap vendor melewati proses verifikasi legalitas, keaslian portofolio, dan rekam jejak profesional sebelum resmi tayang di BrightDor.</p>
                </div>

                <div class="rounded-2xl border border-rose-100 bg-gradient-to-b from-rose-50/50 to-white p-7 shadow-xs hover:shadow-md transition-all">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 ring-1 ring-rose-200 shadow-xs mb-5">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink-900">Rekber &amp; Transaksi Aman</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-600">Dana booking Anda disimpan dengan aman melalui sistem escrow resmi dan baru diteruskan ke vendor setelah kewajiban layanan diselesaikan.</p>
                </div>

                <div class="rounded-2xl border border-rose-100 bg-gradient-to-b from-rose-50/50 to-white p-7 shadow-xs hover:shadow-md transition-all sm:col-span-2 lg:col-span-1">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 ring-1 ring-rose-200 shadow-xs mb-5">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink-900">Transparan Tanpa Biaya Tersembunyi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-600">Bandingkan rincian paket, ulasan autentik pasangan pengantin, dan harga final transparan langsung dari vendor tanpa biaya tak terduga.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
