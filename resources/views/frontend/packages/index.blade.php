@extends('frontend.layouts.app')

@section('title', 'Paket Pernikahan Populer — BrightDor')

@section('content')
    <div class="bd-container py-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-ink-400" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-rose-600 transition-colors">Beranda</a>
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-ink-700">Paket Populer</span>
        </nav>

        {{-- Page Header with Toggle Switcher --}}
        <div class="mt-6 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-rose-100">
            <div>
                <span class="bd-section-kicker">Katalog Paket Pilihan</span>
                <h1 class="font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl mt-1">
                    Paket Pernikahan Populer
                </h1>
                <p class="mt-2 text-sm sm:text-base text-ink-600 max-w-2xl">
                    Temukan paket pernikahan terlengkap dari venue, katering, foto, hingga dekorasi yang paling diminati pasangan pengantin di Indonesia.
                </p>
            </div>

            {{-- Mode Switcher (Jelajahi Vendor vs Paket Populer) --}}
            <div class="inline-flex shrink-0 p-1.5 rounded-2xl bg-rose-50/80 border border-rose-200/80 shadow-2xs">
                <a href="{{ route('vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-ink-600 hover:text-rose-600 transition-all">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                    <span>Jelajahi Vendor</span>
                </a>
                <a href="{{ route('packages.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-bold text-rose-600 shadow-sm ring-1 ring-rose-200/60">
                    <svg class="h-4 w-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/></svg>
                    <span>Paket Populer</span>
                    <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700">{{ $packages->total() }}</span>
                </a>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-12">
            {{-- Desktop filter sidebar --}}
            <aside class="hidden lg:col-span-3 lg:block">
                <form method="GET" action="{{ route('packages.index') }}" class="space-y-6">
                    <input type="hidden" name="sort" value="{{ request('sort', 'popular') }}">

                    {{-- Search keyword --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-700">Cari Paket</h3>
                        <div class="relative mt-2.5">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama paket..."
                                   class="bd-input py-2 pl-9 text-sm">
                            <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                        </div>
                    </div>

                    {{-- Category filter --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-700">Kategori</h3>
                        <div class="mt-3 space-y-2">
                            <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                <input type="radio" name="category" value="" @checked(! request('category'))
                                       class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                <span>Semua Kategori</span>
                            </label>
                            @foreach ($categories as $cat)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="category" value="{{ $cat->id }}" @checked(request('category') == $cat->id)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                    <span class="flex-1">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- City filter --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-700">Kota Lokasi</h3>
                        <div class="mt-3 space-y-2 max-h-56 overflow-y-auto pr-1">
                            <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                <input type="radio" name="city" value="" @checked(! request('city'))
                                       class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                <span>Semua Kota</span>
                            </label>
                            @foreach ($cities as $city => $count)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="city" value="{{ $city }}" @checked(request('city') == $city)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                    <span class="flex-1">{{ $city }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price range --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-700">Range Harga</h3>
                        <div class="mt-3 space-y-2">
                            @foreach ([1, 2, 3, 4, 5] as $key)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="price" value="{{ $key }}" @checked(request('price') == $key)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                    <span>{{ price_range_label((string) $key) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3 text-sm font-bold shadow-sm">
                        Terapkan Filter
                    </button>
                    @if (request()->hasAny(['q', 'category', 'city', 'price', 'capacity']))
                        <a href="{{ route('packages.index') }}" class="block text-center text-xs font-bold text-ink-500 hover:text-rose-600 pt-1">
                            Reset Semua Filter
                        </a>
                    @endif
                </form>
            </aside>

            {{-- Main Results Area --}}
            <div class="lg:col-span-9">
                {{-- Header count & sorting --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                    <p class="text-sm text-ink-600 font-medium">
                        Menampilkan <span class="font-bold text-ink-900">{{ $packages->total() }}</span> paket pernikahan
                        @if (request('q'))
                            untuk "{{ request('q') }}"
                        @endif
                    </p>

                    <form method="GET" action="{{ route('packages.index') }}" class="flex items-center gap-2 text-sm">
                        @foreach (request()->except(['sort', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label for="pkg-sort" class="text-ink-600 font-medium">Urutkan:</label>
                        <select id="pkg-sort" name="sort" onchange="this.form.submit()"
                                class="rounded-[6px] border border-ink-200 bg-white py-2 pl-4 pr-9 text-sm font-semibold text-ink-800 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all cursor-pointer">
                            <option value="popular" @selected(request('sort', 'popular') === 'popular')>Paling Populer</option>
                            <option value="bookings" @selected(request('sort') === 'bookings')>Paling Banyak Dipesan</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Terendah</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Tertinggi</option>
                            <option value="newest" @selected(request('sort') === 'newest')>Paket Terbaru</option>
                        </select>
                    </form>
                </div>

                <div class="bd-divider"></div>

                {{-- Packages Grid --}}
                @if ($packages->isNotEmpty())
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($packages as $pkg)
                            <x-frontend.package-card :service="$pkg"/>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $packages->links() }}
                    </div>
                @else
                    <div class="mt-12 rounded-2xl border border-ink-200/80 bg-ink-50/50 p-12 text-center max-w-lg mx-auto">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 text-rose-600 mb-4">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        </div>
                        <h3 class="font-display text-lg font-bold text-ink-900">Tidak ada paket ditemukan</h3>
                        <p class="mt-1.5 text-sm text-ink-500">Coba ubah kata kunci atau hapus beberapa filter untuk menemukan paket yang sesuai.</p>
                        <a href="{{ route('packages.index') }}" class="bd-btn-primary mt-6 inline-flex py-2.5 px-5 text-sm font-bold">
                            Lihat Semua Paket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
