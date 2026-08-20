@php
    $navCategories = \App\Models\VendorCategory::query()
        ->where('is_active', true)
        ->orderBy('sort_order')->get();
    $activeCategory = request()->route('categorySlug');
@endphp

<header class="sticky top-0 z-40 bg-white border-b border-ink-200/70 shadow-[0_1px_0_rgba(255,255,255,0.9),_0_4px_16px_rgba(0,0,0,0.04)]">
    {{-- Top utility bar --}}
    <div class="hidden bg-ink-100 lg:block">
        <div class="bd-container flex items-center justify-between py-3.5 text-xs text-ink-500">
            <div class="flex items-center gap-2">
                <svg class="h-3.5 w-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75 12 3 2.25 6.75 12 10.5l9.75-3.75ZM2.25 6.75V17l9.75 3.75v-10l-9.75-3.75Zm19.5 0V17L12 20.75"/></svg>
                <span>3000+ vendor pernikahan terpercaya di Indonesia</span>
            </div>
            <div class="flex items-center gap-3 uppercase tracking-[0.08em]">
                @auth
                    <span class="normal-case tracking-normal text-ink-700 font-semibold">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('frontend.login.logout') }}">@csrf<button class="hover:text-rose-600 transition-colors">Keluar</button></form>
                @else
                    <span class="hidden text-ink-500 sm:inline">Daftar Member?</span>
                    <a href="{{ route('frontend.register.create') }}" class="font-semibold text-rose-600 hover:text-rose-700 transition-colors">Daftar</a>
                    <span class="text-ink-300">|</span>
                    <a href="{{ route('frontend.login.create') }}" class="hover:text-rose-600 transition-colors">Masuk</a>
                @endif
                @if (! auth()->check() || auth()->user()->isCouple())
                    <span class="text-ink-300">|</span>
                    <a href="{{ route('vendors.register.create') }}" class="font-semibold text-rose-600 hover:text-rose-700 transition-colors">Are You a Vendor?</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Main header --}}
    <div class="bd-container">
        <div class="flex items-center justify-between gap-4 py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0 group">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-600 text-white ring-1 ring-rose-600/20 shadow-md transition group-hover:shadow-lg group-hover:-translate-y-0.5 duration-300">
                    <x-frontend.ring-icon class="h-5 w-5"/>
                </span>
                <span class="flex flex-col leading-none">
                    <span class="font-display text-2xl font-extrabold tracking-tight text-ink-900">Bright<span class="text-rose-600">Dor</span></span>
                    <span class="mt-0.5 text-[9px] uppercase tracking-[0.22em] text-rose-600 font-bold">Premier Wedding</span>
                </span>
            </a>

            {{-- Desktop search --}}
            <form method="GET" action="{{ route('vendors.index') }}" class="hidden flex-1 max-w-xl md:block">
                <div class="relative group">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400 transition-colors group-focus-within:text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                    <input name="q" value="{{ request('q') }}" type="search"
                           placeholder="Cari vendor, kategori, atau kota..."
                           class="w-full rounded-full border border-ink-200/80 bg-white py-2.5 pl-11 pr-4 text-sm text-ink-700 shadow-sm outline-none transition-all duration-300 placeholder:text-ink-400 focus:border-rose-300 focus:shadow-md focus:ring-4 focus:ring-rose-600/10">
                </div>
            </form>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('vendors.index') }}" class="bd-btn-primary hidden sm:inline-flex text-sm px-5 py-2.5">
                    Jelajahi Vendor
                </a>
                <button data-menu-toggle aria-label="Menu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200/60 hover:border-rose-400/50 hover:bg-rose-50 transition-all duration-300 lg:hidden">
                    <svg class="h-5 w-5 text-ink-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile search --}}
        <form method="GET" action="{{ route('vendors.index') }}" class="relative pb-3 md:hidden">
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari vendor pernikahanmu di sini"
                       class="w-full rounded-full border border-ink-200/80 bg-white py-2.5 pl-11 pr-4 text-sm text-ink-700 shadow-sm outline-none transition-all duration-300 placeholder:text-ink-400 focus:border-rose-300 focus:ring-4 focus:ring-rose-600/10">
            </div>
        </form>
    </div>

    {{-- Category nav --}}
    <nav class="hidden border-t border-ink-200/60 bg-ink-50 lg:block">
        <div class="bd-container flex flex-wrap items-center justify-center gap-0.5">
            <a href="{{ route('home') }}"
               class="whitespace-nowrap px-4 py-3 text-sm transition-all duration-300 {{ request()->routeIs('home') ? 'text-white bg-rose-600 rounded-full mx-1 shadow-md font-bold' : 'text-ink-600 hover:text-rose-600 hover:bg-rose-50 rounded-full font-semibold' }}">
                Home
            </a>
            <a href="{{ route('vendors.index') }}"
               class="whitespace-nowrap px-4 py-3 text-sm transition-all duration-300 {{ request()->routeIs('vendors.index') && ! $activeCategory ? 'text-white bg-rose-600 rounded-full mx-1 shadow-md font-bold' : 'text-ink-600 hover:text-rose-600 hover:bg-rose-50 rounded-full font-semibold' }}">
                Semua Vendor
            </a>
            @foreach ($navCategories as $cat)
                <a href="{{ route('vendors.category', $cat->slug) }}"
                   class="whitespace-nowrap px-4 py-3 text-sm transition-all duration-300 {{ $activeCategory === $cat->slug ? 'text-white bg-rose-600 rounded-full mx-1 shadow-md font-bold' : 'text-ink-600 hover:text-rose-600 hover:bg-rose-50 rounded-full font-medium' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div data-mobile-menu class="hidden border-t border-ink-200/60 bg-white">
        <div class="bd-container space-y-0.5 px-4 py-4">
            <a href="{{ route('home') }}" class="block rounded-md px-4 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Home</a>
            <a href="{{ route('vendors.index') }}" class="block rounded-md px-4 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Semua Vendor</a>
            @foreach ($navCategories as $cat)
                <a href="{{ route('vendors.category', $cat->slug) }}" class="block rounded-md px-4 py-2.5 text-sm text-ink-600 hover:bg-rose-50 hover:text-rose-600 transition-colors">{{ $cat->name }}</a>
            @endforeach
            <div class="mt-3 pt-3 border-t border-ink-200/60">
                <a href="{{ route('vendors.index') }}" class="bd-btn-primary w-full text-center text-sm">Jelajahi Vendor</a>
            </div>
        </div>
    </div>
</header>
