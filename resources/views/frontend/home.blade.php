@extends('frontend.layouts.app')

@section('title', 'Vendor Pernikahan Terlengkap')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-rose-50 via-rose-50/60 to-white">
        <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full border border-rose-300/40"></div>
        <div class="pointer-events-none absolute -bottom-40 left-1/3 h-96 w-96 rounded-full border border-rose-300/30"></div>
        <div class="pointer-events-none absolute left-10 top-1/3 h-48 w-48 rounded-full border border-rose-300/25"></div>
        <div class="pointer-events-none absolute -left-24 bottom-10 h-64 w-64 rounded-full bg-rose-100/40 blur-3xl"></div>

        <div class="bd-container relative py-20 text-center sm:py-24">
            <span class="bd-section-kicker inline-block">Premium Wedding Marketplace</span>
            <h1 class="mt-5 font-display text-3xl font-extrabold leading-[1.15] tracking-tight text-ink-900 sm:text-4xl lg:text-5xl">
                Wujudkan Pernikahan<br>
                Impianmu Bersama
                <span class="text-rose-600">BrightDor</span>
            </h1>
            <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-ink-500">
                Temukan {{ $totalVendors }}+ vendor pernikahan premium — venue, katering, dekorasi, fotografer, dan masih banyak lagi — dalam satu platform terpercaya.
            </p>

            <form method="GET" action="{{ route('vendors.index') }}" class="mx-auto mt-10 max-w-2xl">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                        <input name="q" type="search" placeholder="Cari venue, katering, fotografer, kota..."
                               class="bd-input pl-11 py-3.5 rounded-full shadow-sm">
                    </div>
                    <button type="submit" class="bd-btn-primary py-3.5">
                        Cari Vendor
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Categories --}}
    <section class="bd-section">
        <div class="bd-container">
            <div class="flex items-end justify-between">
                <div>
                    <span class="bd-section-kicker">Kategori</span>
                    <h2 class="bd-section-title mt-2">Cari Berdasarkan Kebutuhanmu</h2>
                </div>
                <a href="{{ route('vendors.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                    Lihat Semua
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($categories as $cat)
                    <a href="{{ route('vendors.category', $cat->slug) }}"
                       class="group bd-card p-6 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600 ring-1 ring-rose-200 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:ring-rose-600 group-hover:shadow-lg group-hover:-translate-y-0.5">
                            <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-6 w-6"/>
                        </span>
                        <span class="mt-4 block font-display text-sm font-bold text-ink-900">{{ $cat->name }}</span>
                        <span class="mt-1 block text-xs text-ink-400 font-medium">{{ $cat->vendors_count }} vendor</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured vendors --}}
    @if ($featuredVendors->isNotEmpty())
        <section class="bg-ink-50 border-y border-ink-200/70">
            <div class="bd-section bd-container">
                <div class="flex items-end justify-between">
                    <div>
                        <span class="bd-section-kicker">Pilihan Editor</span>
                        <h2 class="bd-section-title mt-2">Vendor Unggulan</h2>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                        Lihat Semua
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredVendors as $vendor)
                        <x-frontend.vendor-card :vendor="$vendor"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
