@extends('frontend.layouts.app')

@section('title', ($category->name ?? 'Semua Vendor') . ' di BrightDor')

@section('content')
    <div class="bd-container py-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-ink-400" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-rose-600 transition-colors">Beranda</a>
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('vendors.index') }}" class="hover:text-rose-600 transition-colors">Vendor</a>
            @if ($category)
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('vendors.category', $category->slug) }}" class="font-semibold text-ink-700 hover:text-rose-600 transition-colors">{{ $category->name }}</a>
            @endif
            @if (request('city'))
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <span class="font-semibold text-ink-700">{{ request('city') }}</span>
            @endif
        </nav>

        {{-- Page header --}}
        <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="bd-section-title">
                    {{ $category ? $category->name . ' di ' . (request('city') ?: 'Indonesia') : (request('city') ? 'Semua Vendor di ' . request('city') : 'Semua Vendor') }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink-500">
                    Daftar vendor {{ $category ? mb_strtolower($category->name) : 'pernikahan' }} terpercaya
                    {{ request('city') ? 'di ' . request('city') : 'di seluruh Indonesia' }} untuk mempersiapkan hari bahagiamu.
                </p>
            </div>

            <button data-filter-toggle class="inline-flex items-center gap-2 rounded-[5px] border border-ink-200 bg-white px-4 py-2.5 text-sm font-bold text-ink-700 shadow-sm transition-all duration-300 hover:border-rose-500/50 hover:text-rose-600 hover:shadow-md lg:hidden">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c4.97 0 9 1.79 9 4s-4.03 4-9 4-9-1.79-9-4 4.03-4 9-4Zm-9 4v12c0 2.21 4.03 4 9 4s9-1.79 9-4V7"/></svg>
                Filter
            </button>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-12">
            {{-- Sidebar filters --}}
            <aside data-filter-panel class="hidden lg:block lg:col-span-3">
                <form method="GET" action="{{ $category ? route('vendors.category', $category->slug) : route('vendors.index') }}" class="space-y-5">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">

                    {{-- Kategori --}}
                    @if (! $category)
                        <div class="bd-card p-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kategori</h3>
                            <div class="mt-3 space-y-2.5">
                                @foreach ($categories as $cat)
                                    <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                        <input type="checkbox" name="category" value="{{ $cat->id }}"
                                               @checked(request('category') == $cat->id)
                                               class="h-4 w-4 rounded border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                                        <span class="flex-1">{{ $cat->name }}</span>
                                        <span class="text-xs text-ink-400">{{ $cat->vendors_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            Semua Kategori
                        </a>
                    @endif

                    {{-- Kota --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kota</h3>
                        <div class="mt-3 max-h-64 space-y-2.5 overflow-y-auto pr-1 scrollbar-thin">
                            @foreach ($cities as $city => $count)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="checkbox" name="city" value="{{ $city }}"
                                           @checked(request('city') == $city)
                                           class="h-4 w-4 rounded border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                                    <span class="flex-1">{{ $city }}</span>
                                    <span class="text-xs text-ink-400">{{ $count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Range Harga</h3>
                        <div class="mt-3 space-y-2.5">
                            @foreach ([1, 2, 3, 4, 5] as $key)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="price" value="{{ $key }}"
                                           @checked(request('price') == $key)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                                    <span>{{ price_range_label((string) $key) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kapasitas Tamu</h3>
                        <div class="mt-3 space-y-2.5">
                            @foreach ([1, 2, 3, 4, 5] as $key)
                                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="capacity" value="{{ $key }}"
                                           @checked(request('capacity') == $key)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                                    <span>{{ capacity_range_label((string) $key) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="bd-card p-5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Rating</h3>
                        <div class="mt-3 space-y-2.5">
                            @foreach ([4.5, 4, 3, 2] as $min)
                                <label class="group flex cursor-pointer items-center gap-2 text-sm text-ink-600 transition-colors hover:text-rose-600">
                                    <input type="radio" name="rating" value="{{ $min }}"
                                           @checked((float) request('rating') == $min)
                                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                                    <span class="inline-flex items-center gap-1">
                                        <x-frontend.rating-stars :rating="$min" size="sm"/>
                                        <span class="ml-0.5 text-xs text-ink-400">&amp; lebih</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-2.5">
                        Terapkan Filter
                    </button>
                </form>
            </aside>

            {{-- Mobile filter drawer --}}
            <div data-mobile-filter-panel class="fixed inset-0 z-[200] hidden">
                <div class="absolute inset-0 bg-ink-900/40 backdrop-blur-sm" data-filter-close></div>
                <div class="absolute inset-y-0 left-0 w-[85%] max-w-sm overflow-y-auto bg-white p-6 shadow-2xl">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="font-display text-xl font-bold text-ink-900">Filter Vendor</h3>
                        <button data-filter-close class="flex h-9 w-9 items-center justify-center rounded-full border border-ink-200/60 hover:bg-rose-50 transition-colors">
                            <svg class="h-4 w-4 text-ink-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form method="GET" action="{{ $category ? route('vendors.category', $category->slug) : route('vendors.index') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @include('frontend.vendors.partials.filter-fields')
                        <button type="submit" class="bd-btn-primary w-full justify-center mt-6 py-3">Terapkan Filter</button>
                    </form>
                </div>
            </div>

            {{-- Results --}}
            <div class="lg:col-span-9">
                {{-- Results header: count + sort --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                    <p class="text-sm text-ink-500">
                        <span class="font-bold text-ink-900">{{ $vendors->total() }}</span>
                        result{{ $vendors->total() > 1 ? 's' : '' }}
                        @if (request('q'))
                            untuk "{{ request('q') }}"
                        @endif
                    </p>

                    <div class="flex items-center gap-2 text-sm">
                        <label for="sort" class="text-ink-500">Urutkan:</label>
                        <select id="sort" name="sort" data-sort-select
                                class="rounded-[5px] border border-ink-200 bg-white py-2 pl-4 pr-9 text-sm text-ink-700 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                            <option value="popular" @selected(request('sort', 'popular') === 'popular')>Terpopuler</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Terendah</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Tertinggi</option>
                            <option value="rating" @selected(request('sort') === 'rating')>Rating Tertinggi</option>
                            <option value="featured" @selected(request('sort') === 'featured')>Vendor Unggulan</option>
                        </select>
                    </div>
                </div>

                <div class="bd-divider"></div>

                {{-- Active filter chips --}}
                @php
                    $activeFilters = collect();
                    if (request('q')) $activeFilters->push(['label' => '"' . request('q') . '"', 'type' => 'q']);
                    if (request('city')) $activeFilters->push(['label' => request('city'), 'type' => 'city']);
                    if (request('price')) $activeFilters->push(['label' => price_range_label(request('price')), 'type' => 'price']);
                    if (request('capacity')) $activeFilters->push(['label' => capacity_range_label(request('capacity')), 'type' => 'capacity']);
                    if (request('rating')) $activeFilters->push(['label' => 'Rating ' . request('rating') . '+', 'type' => 'rating']);
                    $filterBaseUrl = $category ? route('vendors.category', $category->slug) : route('vendors.index');
                    $resetUrl = $filterBaseUrl;
                @endphp

                @if ($activeFilters->isNotEmpty() || request('category'))
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @foreach ($activeFilters as $filter)
                            <a href="{{ $filterBaseUrl . '?' . active_filter_query([], [$filter['type']]) }}"
                               class="inline-flex items-center gap-1.5 rounded-[4px] border border-ink-200 bg-white px-3 py-1.5 text-xs text-ink-600 shadow-sm transition-all hover:border-rose-400 hover:bg-rose-50">
                                {{ $filter['label'] }}
                                <svg class="h-3 w-3 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                            </a>
                        @endforeach
                        <a href="{{ $resetUrl }}"
                           class="inline-flex items-center gap-1.5 rounded-[4px] bg-ink-100 px-3 py-1.5 text-xs font-semibold text-ink-600 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                            Reset Semua
                        </a>
                    </div>
                @endif

                {{-- Grid --}}
                @if ($vendors->count())
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($vendors as $vendor)
                            <x-frontend.vendor-card :vendor="$vendor"/>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $vendors->links() }}
                    </div>
                @else
                    <div class="mt-10 flex flex-col items-center rounded-[5px] border border-dashed border-ink-200 bg-white py-20 text-center shadow-sm">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14Z"/></svg>
                        </span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink-900">Vendor Tidak Ditemukan</h3>
                        <p class="mt-2 max-w-sm text-sm text-ink-500">Coba ubah kata kunci atau hapus filter untuk melihat lebih banyak vendor.</p>
                        <a href="{{ $resetUrl }}" class="bd-btn-primary mt-6">Reset Pencarian</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const sortSelect = document.querySelector('[data-sort-select]');
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                const url = new URL(window.location.href);
                if (sortSelect.value === 'popular') {
                    url.searchParams.delete('sort');
                } else {
                    url.searchParams.set('sort', sortSelect.value);
                }
                window.location.href = url.toString();
            });
        }

        document.querySelectorAll('[data-filter-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelector('[data-mobile-filter-panel]')?.classList.remove('hidden');
            });
        });
        document.querySelectorAll('[data-filter-close]').forEach(el => {
            el.addEventListener('click', () => {
                el.closest('[data-mobile-filter-panel]')?.classList.add('hidden');
            });
        });
    </script>
@endsection
