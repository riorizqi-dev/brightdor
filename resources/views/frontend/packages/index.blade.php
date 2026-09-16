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

        {{-- Page Header --}}
        <div class="mt-6 flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-ink-100">
            <div>
                <h1 class="font-display text-2xl font-semibold tracking-tight text-ink-900 sm:text-3xl">
                    Paket Pernikahan Populer
                </h1>
                <p class="mt-1.5 text-sm text-ink-500 max-w-2xl">
                    Paket dari berbagai vendor yang paling banyak dipesan. Bandingkan harga dan isinya sebelum memilih.
                </p>
            </div>

            <a href="{{ route('vendors.index') }}" class="hidden sm:inline-flex shrink-0 items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-700 transition-colors hover:border-rose-400 hover:text-rose-600">
                Jelajahi Vendor
            </a>
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
