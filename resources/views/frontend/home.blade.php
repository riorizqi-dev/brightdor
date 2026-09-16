@extends('frontend.layouts.app')

@section('title', 'Marketplace Vendor Pernikahan')

@section('content')
    {{-- Hero: clean, search-focused (Airbnb/Traveloka style) --}}
    <section class="border-b border-ink-100 bg-white">
        <div class="bd-container">
            <div class="mx-auto max-w-3xl pb-10 pt-12 text-center sm:pb-14 sm:pt-16">
                <h1 class="hero-enter-2 font-display text-3xl font-semibold leading-tight tracking-tight text-ink-900 sm:text-4xl lg:text-[2.9rem]">
                    Semua vendor pernikahan,<br class="hidden sm:block"> dalam satu tempat.
                </h1>
                <p class="hero-enter-3 mx-auto mt-4 max-w-xl text-base leading-relaxed text-ink-500 sm:text-lg">
                    Bandingkan {{ $totalVendors }}+ venue, katering, dekorasi, dan fotografer. Lihat harga transparan, baca ulasan asli, lalu booking langsung.
                </p>

                {{-- Search: the hero's single focal point --}}
                <form method="GET" action="{{ route('vendors.index') }}" class="hero-enter-3 mx-auto mt-8 max-w-2xl">
                    <div class="flex items-center gap-2 rounded-full border border-ink-200 bg-white p-2 shadow-[0_8px_30px_rgba(42,37,32,0.08)] transition-shadow focus-within:border-rose-400 focus-within:shadow-[0_8px_36px_rgba(194,56,102,0.14)]">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                            <input name="q" type="search" placeholder="Cari venue, katering, fotografer, kota..."
                                   class="w-full rounded-full border-0 bg-transparent py-3 pl-12 pr-4 text-sm text-ink-800 placeholder:text-ink-400 focus:outline-none focus:ring-0">
                        </div>
                        <button type="submit" class="bd-btn-primary rounded-full px-7 py-3">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- Quick category chips --}}
                <div class="hero-enter-4 mt-6 flex flex-wrap items-center justify-center gap-2">
                    @foreach ($categories->take(6) as $cat)
                        <a href="{{ route('vendors.category', $cat->slug) }}"
                           class="rounded-full border border-ink-200 bg-white px-4 py-1.5 text-xs font-semibold text-ink-600 transition-colors hover:border-rose-400 hover:text-rose-600">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Featured vendors: clean product grid --}}
    @if ($featuredVendors->isNotEmpty())
        <section id="unggulan" class="bd-section scroll-mt-20">
            <div class="bd-container">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="bd-section-title">Vendor Unggulan</h2>
                        <p class="mt-1.5 text-sm text-ink-500">Pilihan terbaik minggu ini dari tim kami.</p>
                    </div>
                    <a href="{{ route('vendors.index', ['sort' => 'featured']) }}" class="hidden shrink-0 items-center gap-1.5 text-sm font-semibold text-rose-600 hover:text-rose-700 sm:inline-flex">
                        Lihat Semua
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredVendors as $vendor)
                        <x-frontend.vendor-card :vendor="$vendor"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Categories: simple, scannable --}}
    <section id="kategori" class="border-t border-ink-100 bg-ink-50/50 scroll-mt-20">
        <div class="bd-section bd-container">
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="bd-section-title">Jelajahi Kategori</h2>
                    <p class="mt-1.5 text-sm text-ink-500">Temukan vendor sesuai kebutuhan acaramu.</p>
                </div>
                <a href="{{ route('vendors.index') }}" class="hidden shrink-0 items-center gap-1.5 text-sm font-semibold text-rose-600 hover:text-rose-700 sm:inline-flex">
                    Lihat Semua
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="mt-7 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($categories as $cat)
                    <a href="{{ route('vendors.category', $cat->slug) }}"
                       class="group flex flex-col items-center rounded-2xl border border-ink-200/80 bg-white p-5 text-center transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition-colors group-hover:bg-rose-600 group-hover:text-white">
                            <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-5 w-5"/>
                        </span>
                        <span class="mt-3 block text-sm font-semibold text-ink-900 group-hover:text-rose-600">{{ $cat->name }}</span>
                        <span class="mt-0.5 block text-xs text-ink-400">{{ $cat->vendors_count }} vendor</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Value props: clean 3-col, no heavy cards --}}
    <section id="keunggulan" class="scroll-mt-20 border-t border-ink-100 bg-white">
        <div class="bd-section bd-container">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="bd-section-title">Kenapa lewat BrightDor?</h2>
                <p class="mt-3 text-base text-ink-500">Kami bantu kamu menemukan vendor yang tepat dan memastikan transaksinya aman sampai acara selesai.</p>
            </div>

            <div class="mx-auto mt-12 grid max-w-4xl gap-10 sm:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-ink-900">Vendor Ditinjau Dulu</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-500">Setiap vendor kami periksa legalitas dan portofolionya sebelum tampil di platform.</p>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-ink-900">Bayar Lewat Rekber</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-500">Uang kamu ditahan dulu dan baru diteruskan ke vendor setelah acara selesai.</p>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-ink-900">Harga Jelas di Depan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-500">Semua paket menampilkan harga final. Bandingkan beberapa vendor tanpa biaya tersembunyi.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
