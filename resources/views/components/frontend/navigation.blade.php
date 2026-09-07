@php
    $navCategories = \App\Models\VendorCategory::query()
        ->where('is_active', true)
        ->orderBy('sort_order')->get();
    $activeCategory = request()->route('categorySlug');
    // Show first 5 categories inline; rest go into "Lainnya" dropdown
    $inlineCategories = $navCategories->take(5);
    $overflowCategories = $navCategories->slice(5);

    // Resolve valid published invitation slug for preview (returns 200 OK)
    $invitationSlug = \App\Models\Invitation::query()
        ->where('is_published', true)
        ->value('slug') ?? 'sinta-budi-1';
@endphp

<header id="main-navbar" class="navbar-scrollable fixed inset-x-0 top-0 z-[100] transition-all duration-300 ease-out bg-white/95 backdrop-blur-md border-b border-ink-200/60 shadow-[0_2px_12px_rgba(0,0,0,0.03)]">
    {{-- Main header bar (Logo + Navigation + Auth CTA) --}}
    <div class="bd-container bd-navbar-main flex items-center justify-between gap-4 px-4 sm:px-6 py-3.5">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-tr from-rose-700 to-rose-500 text-white shadow-md shadow-rose-600/20 ring-1 ring-rose-600/20 transition group-hover:shadow-lg group-hover:-translate-y-0.5 duration-300">
                <x-frontend.ring-icon class="h-5.5 w-5.5"/>
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-display text-2xl font-extrabold tracking-tight text-ink-900">Bright<span class="text-rose-600">Dor</span></span>
                <span class="mt-0.5 text-[10px] uppercase tracking-[0.22em] text-rose-600 font-bold">Premier Wedding</span>
            </span>
        </a>

        {{-- Primary Curated Navigation (Spacious, Whitespace-Nowrap, Never Wraps) --}}
        <nav class="hidden xl:flex items-center gap-2 shrink-0" aria-label="Navigasi Utama">
            <a href="{{ route('vendors.index') }}"
               class="whitespace-nowrap px-4 py-2.5 text-sm font-semibold transition-all duration-200 rounded-full {{ request()->routeIs('vendors.index') && ! $activeCategory ? 'bg-rose-50 text-rose-700 font-bold shadow-2xs' : 'text-ink-700 hover:text-rose-600 hover:bg-rose-50/70' }}">
                Jelajahi Vendor
            </a>
            <a href="{{ route('packages.index') }}"
               class="whitespace-nowrap px-4 py-2.5 text-sm font-semibold transition-all duration-200 rounded-full {{ request()->routeIs('packages.index') ? 'bg-rose-50 text-rose-700 font-bold shadow-2xs' : 'text-ink-700 hover:text-rose-600 hover:bg-rose-50/70' }}">
                Paket Populer
            </a>
            <a href="{{ route('invitations.show', $invitationSlug) }}"
               class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-ink-700 transition-all duration-200 rounded-full hover:text-rose-600 hover:bg-rose-50/70">
                <span>Undangan Digital</span>
                <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700 tracking-wide uppercase">Preview</span>
            </a>
            <a href="{{ url('/#keunggulan') }}"
               class="whitespace-nowrap px-4 py-2.5 text-sm font-semibold text-ink-700 transition-all duration-200 rounded-full hover:text-rose-600 hover:bg-rose-50/70">
                Kenapa BrightDor?
            </a>
        </nav>

        {{-- Actions & Auth (Desktop) --}}
        <div class="hidden md:flex items-center gap-3 shrink-0">
            {{-- Vendor onboarding CTA for guests --}}
            @guest
                <a href="{{ route('vendors.register.create') }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50/90 px-4 py-2 text-sm font-bold text-amber-900 shadow-2xs transition-all duration-300 hover:border-amber-400 hover:bg-amber-100 hover:shadow-xs whitespace-nowrap">
                    <svg class="h-4 w-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                    <span>Jadi Mitra Vendor</span>
                </a>
            @endguest

            @auth
                @php
                    $userName = auth()->user()->name;
                    $initials = collect(explode(' ', str_replace('&', ' ', $userName)))
                        ->filter()
                        ->take(2)
                        ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
                        ->join('');
                    if (empty($initials)) {
                        $initials = 'U';
                    }
                @endphp

                <div class="flex items-center gap-3">
                    @if (auth()->user()->isCouple())
                        <a href="{{ route('my-bookings.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-bold text-rose-700 hover:bg-rose-100 hover:border-rose-300 transition-all duration-200 shadow-2xs whitespace-nowrap">
                            <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            <span>Booking Saya</span>
                        </a>
                    @endif

                    {{-- User Profile Pill & Dropdown --}}
                    <div class="relative" data-dropdown>
                        <button type="button" data-dropdown-toggle aria-expanded="false" class="group flex items-center gap-2.5 rounded-full border border-ink-200/90 bg-white py-1.5 pl-1.5 pr-3 shadow-2xs transition-all duration-200 hover:border-rose-300 hover:bg-rose-50/50 hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 cursor-pointer">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 text-xs font-black text-white shadow-2xs ring-2 ring-rose-100">
                                {{ $initials }}
                            </span>
                            <span class="flex flex-col text-left leading-tight">
                                <span class="max-w-[140px] truncate text-xs font-bold text-ink-900 group-hover:text-rose-700 transition-colors">
                                    {{ $userName }}
                                </span>
                                <span class="text-[10px] font-semibold text-ink-400">
                                    @if (auth()->user()->user_type === 'admin')
                                        Admin Platform
                                    @elseif (auth()->user()->isVendor())
                                        Mitra Vendor
                                    @else
                                        Calon Pengantin
                                    @endif
                                </span>
                            </span>
                            <svg class="h-3.5 w-3.5 text-ink-400 group-hover:text-rose-600 transition-transform duration-200 ml-0.5" data-dropdown-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div data-dropdown-menu class="invisible absolute right-0 top-full z-[120] mt-2 w-64 origin-top-right rounded-2xl border border-ink-200/90 bg-white p-2 opacity-0 shadow-xl shadow-ink-900/10 backdrop-blur-xl transition-all duration-200">
                            {{-- Dropdown Header with User Info --}}
                            <div class="px-3 py-2.5 rounded-xl bg-ink-50/70 border border-ink-100 mb-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 text-xs font-extrabold text-white shadow-2xs">
                                        {{ $initials }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-ink-900">{{ $userName }}</p>
                                        <p class="truncate text-[11px] text-ink-500">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-ink-200/50 flex items-center justify-between text-[11px]">
                                    <span class="text-ink-500 font-medium">Status Akun:</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                </div>
                            </div>

                            {{-- Menu Links --}}
                            <div class="py-1 space-y-0.5">
                                @if (auth()->user()->isCouple())
                                    <a href="{{ route('my-bookings.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-ink-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                        <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                        <span>Booking &amp; Pesanan Saya</span>
                                    </a>
                                    <a href="{{ route('vendors.register.create') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-ink-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                        <svg class="h-4 w-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                                        <span>Daftar Jadi Mitra Vendor</span>
                                    </a>
                                @endif

                                @if (auth()->user()->isVendor())
                                    <a href="{{ url('/vendor') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-ink-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                        <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                                        <span>Panel Mitra Vendor</span>
                                    </a>
                                @endif

                                @if (auth()->user()->user_type === 'admin')
                                    <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-ink-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                        <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                        <span>Panel Admin</span>
                                    </a>
                                @endif

                                <a href="{{ route('packages.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-ink-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                    <svg class="h-4 w-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span>Paket Pernikahan Populer</span>
                                </a>
                            </div>

                            {{-- Logout Item --}}
                            <div class="mt-1 pt-1 border-t border-ink-100">
                                <form method="POST" action="{{ route('frontend.login.logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50/80 transition-colors cursor-pointer">
                                        <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('frontend.login.create') }}" class="text-sm font-bold text-ink-700 hover:text-rose-600 px-4 py-2 transition-colors whitespace-nowrap">
                    Masuk
                </a>
                <a href="{{ route('frontend.register.create') }}" class="inline-flex items-center justify-center rounded-full bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold px-5 py-2 shadow-sm transition-all duration-200 whitespace-nowrap hover:shadow-md">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger & Quick Login --}}
        <div class="flex items-center gap-2.5 md:hidden">
            @guest
                <a href="{{ route('frontend.login.create') }}" class="text-sm font-bold text-rose-600 px-3.5 py-1.5 rounded-full border border-rose-200 bg-rose-50">Masuk</a>
            @endguest
            <button data-menu-toggle aria-label="Menu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200/80 hover:border-rose-400 hover:bg-rose-50 transition-all duration-300 cursor-pointer">
                <svg class="h-6 w-6 text-ink-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile search bar --}}
    <form method="GET" action="{{ route('vendors.index') }}" class="relative px-4 pb-2.5 md:hidden">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
            <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari vendor pernikahanmu..."
                   class="w-full rounded-full border border-ink-200/80 bg-ink-50/60 py-2 pl-9 pr-4 text-xs text-ink-800 placeholder:text-ink-400 focus:bg-white focus:border-rose-300 focus:ring-2 focus:ring-rose-500/15 focus:outline-none">
        </div>
    </form>

    {{-- Category navigation (desktop only) --}}
    <nav class="navbar-catnav hidden border-t border-ink-100/90 bg-white/95 lg:block">
        <div class="bd-container px-6">
            <div class="bd-catnav flex items-center justify-center gap-1.5 py-2 overflow-visible">
                <a href="{{ route('home') }}"
                   class="bd-catnav-item flex-shrink-0 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-700 transition-all duration-300 {{ request()->routeIs('home') ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                    Home
                </a>
                <a href="{{ route('vendors.index') }}"
                   class="bd-catnav-item flex-shrink-0 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-700 transition-all duration-300 {{ request()->routeIs('vendors.index') && ! $activeCategory ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                    Semua Vendor
                </a>
                @foreach ($inlineCategories as $cat)
                    <a href="{{ route('vendors.category', $cat->slug) }}"
                       class="bd-catnav-item flex-shrink-0 inline-flex items-center gap-2 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-700 transition-all duration-300 {{ $activeCategory === $cat->slug ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                        <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-4.5 w-4.5 shrink-0 {{ $activeCategory === $cat->slug ? 'text-white' : 'text-rose-500' }}"/>
                        <span>{{ $cat->name }}</span>
                    </a>
                @endforeach

                {{-- "Lainnya" dropdown for overflow categories --}}
                @if ($overflowCategories->isNotEmpty())
                    <div class="bd-catnav-dropdown relative flex-shrink-0" data-dropdown>
                        <button type="button" data-dropdown-toggle aria-expanded="false"
                                class="bd-catnav-item flex items-center gap-2 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-700 transition-all duration-300 hover:text-rose-600 hover:bg-rose-50 rounded-full cursor-pointer">
                            <span>Lainnya</span>
                            <svg class="h-3.5 w-3.5 transition-transform duration-200" data-dropdown-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div data-dropdown-menu
                             class="invisible absolute left-0 top-full z-[110] mt-1.5 min-w-[220px] origin-top-left rounded-xl border border-ink-200/70 bg-white/98 p-2 opacity-0 shadow-xl shadow-ink-900/10 backdrop-blur-xl transition-all duration-200">
                            @foreach ($overflowCategories as $cat)
                                <a href="{{ route('vendors.category', $cat->slug) }}"
                                   class="flex items-center gap-2.5 rounded-lg px-3.5 py-2.5 text-sm font-medium {{ $activeCategory === $cat->slug ? 'bg-rose-50 text-rose-700 font-bold' : 'text-ink-700 hover:bg-rose-50 hover:text-rose-600' }} transition-colors">
                                    <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-4.5 w-4.5 shrink-0 text-rose-500"/>
                                    <span>{{ $cat->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Mobile menu panel --}}
    <div data-mobile-menu class="hidden border-t border-ink-200/60 bg-white/98 backdrop-blur-md">
        <div class="bd-container flex flex-col gap-1.5 px-5 py-4">
            <a href="{{ route('home') }}" class="block rounded-lg px-3.5 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Home</a>
            <a href="{{ route('vendors.index') }}" class="block rounded-lg px-3.5 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Jelajahi Semua Vendor</a>
            <a href="{{ route('packages.index') }}" class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-ink-800 hover:bg-rose-50 transition-colors">Paket Populer</a>
            <a href="{{ route('invitations.show', $invitationSlug) }}" class="flex items-center justify-between rounded-lg px-3.5 py-2.5 text-sm font-semibold text-ink-800 hover:bg-rose-50 transition-colors">
                <span>Undangan Digital</span>
                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700">Preview</span>
            </a>
            <a href="{{ url('/#keunggulan') }}" class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-ink-800 hover:bg-rose-50 transition-colors">Kenapa BrightDor?</a>
            <div class="my-1.5 border-t border-ink-100"></div>
            <p class="px-3.5 text-xs font-bold uppercase tracking-wider text-ink-400">Kategori Pilihan</p>
            @foreach ($navCategories as $cat)
                <a href="{{ route('vendors.category', $cat->slug) }}" class="flex items-center gap-2.5 rounded-lg px-3.5 py-2 text-sm font-medium text-ink-700 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                    <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-4 w-4 text-rose-500"/>
                    {{ $cat->name }}
                </a>
            @endforeach
            <div class="my-2 border-t border-ink-100"></div>
            @auth
                @php
                    $mobileUserName = auth()->user()->name;
                    $mobileInitials = collect(explode(' ', str_replace('&', ' ', $mobileUserName)))
                        ->filter()
                        ->take(2)
                        ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
                        ->join('');
                    if (empty($mobileInitials)) {
                        $mobileInitials = 'U';
                    }
                @endphp
                <div class="rounded-2xl border border-ink-200/80 bg-ink-50/70 p-3.5 mb-2">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-rose-600 to-pink-500 text-sm font-black text-white shadow-2xs ring-2 ring-rose-100">
                            {{ $mobileInitials }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-ink-900">{{ $mobileUserName }}</p>
                            <p class="truncate text-xs text-ink-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-ink-200/60 flex flex-col gap-1.5">
                        @if (auth()->user()->isCouple())
                            <a href="{{ route('my-bookings.index') }}" class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2 text-xs font-bold text-rose-700 shadow-2xs border border-rose-200/80 hover:bg-rose-50 transition-colors">
                                <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                <span>Booking &amp; Pesanan Saya</span>
                            </a>
                        @endif
                        @if (auth()->user()->isVendor())
                            <a href="{{ url('/vendor') }}" class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2 text-xs font-bold text-rose-700 shadow-2xs border border-rose-200/80 hover:bg-rose-50 transition-colors">
                                <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                                <span>Panel Mitra Vendor</span>
                            </a>
                        @endif
                        @if (auth()->user()->user_type === 'admin')
                            <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2 text-xs font-bold text-rose-700 shadow-2xs border border-rose-200/80 hover:bg-rose-50 transition-colors">
                                <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                <span>Panel Admin</span>
                            </a>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('frontend.login.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50/90 px-4 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors cursor-pointer">
                        <svg class="h-4 w-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <a href="{{ route('vendors.register.create') }}" class="flex items-center justify-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-900 shadow-xs mb-2">
                    <svg class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/></svg>
                    <span>Jadi Mitra Vendor</span>
                </a>
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('frontend.login.create') }}" class="flex items-center justify-center rounded-full border border-ink-300 px-4 py-2.5 text-sm font-bold text-ink-700 hover:bg-ink-50">Masuk</a>
                    <a href="{{ route('frontend.register.create') }}" class="flex items-center justify-center rounded-full bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-rose-700">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // "Lainnya" dropdown — works on both click and keyboard
    document.querySelectorAll('[data-dropdown]').forEach(function (wrapper) {
        const toggle = wrapper.querySelector('[data-dropdown-toggle]');
        const menu = wrapper.querySelector('[data-dropdown-menu]');
        const chevron = wrapper.querySelector('[data-dropdown-chevron]');
        if (!toggle || !menu) return;

        function open() {
            menu.classList.remove('invisible', 'opacity-0', 'scale-95');
            menu.classList.add('visible', 'opacity-100', 'scale-100');
            toggle.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
        function close() {
            menu.classList.add('invisible', 'opacity-0', 'scale-95');
            menu.classList.remove('visible', 'opacity-100', 'scale-100');
            toggle.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = '';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            // close all other dropdowns first
            document.querySelectorAll('[data-dropdown]').forEach(function (other) {
                if (other !== wrapper) {
                    const t = other.querySelector('[data-dropdown-toggle]');
                    const m = other.querySelector('[data-dropdown-menu]');
                    if (t && m) {
                        m.classList.add('invisible', 'opacity-0', 'scale-95');
                        m.classList.remove('visible', 'opacity-100', 'scale-100');
                        t.setAttribute('aria-expanded', 'false');
                        const c = other.querySelector('[data-dropdown-chevron]');
                        if (c) c.style.transform = '';
                    }
                }
            });
            isOpen ? close() : open();
        });

        // Hover open on desktop (pointer:fine)
        if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            let hoverTimer;
            wrapper.addEventListener('mouseenter', function () {
                clearTimeout(hoverTimer);
                hoverTimer = setTimeout(open, 80);
            });
            wrapper.addEventListener('mouseleave', function () {
                clearTimeout(hoverTimer);
                hoverTimer = setTimeout(close, 150);
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-dropdown]')) {
            document.querySelectorAll('[data-dropdown] [data-dropdown-menu]').forEach(function (menu) {
                menu.classList.add('invisible', 'opacity-0', 'scale-95');
                menu.classList.remove('visible', 'opacity-100', 'scale-100');
                const t = menu.parentElement.querySelector('[data-dropdown-toggle]');
                if (t) t.setAttribute('aria-expanded', 'false');
                const c = menu.parentElement.querySelector('[data-dropdown-chevron]');
                if (c) c.style.transform = '';
            });
        }
    });

    // Liquid glass scroll effect — single layer on the whole header
    const navbar = document.getElementById('main-navbar');
    if (!navbar) return;

    const threshold = 48;
    function onScroll() {
        if (window.scrollY > threshold) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }

    let ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(function () {
                onScroll();
                ticking = false;
            });
            ticking = true;
        }
    });

    onScroll();
});
</script>
@endpush
