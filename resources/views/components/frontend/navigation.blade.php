@php
    $navCategories = \App\Models\VendorCategory::query()
        ->where('is_active', true)
        ->orderBy('sort_order')->get();
    $activeCategory = request()->route('categorySlug');
    // Show first 5 categories inline; rest go into "Lainnya" dropdown
    $inlineCategories = $navCategories->take(5);
    $overflowCategories = $navCategories->slice(5);
@endphp

<header id="main-navbar" class="navbar-scrollable fixed inset-x-0 top-0 z-[100] transition-all duration-500 ease-out">
    {{-- Promo topbar (desktop only) --}}
    <div class="navbar-topbar hidden border-b border-ink-200/50 bg-ink-50 lg:block">
        <div class="bd-container flex items-center justify-between px-6 py-3 text-xs text-ink-500">
            {{-- Trust indicator as a micro-badge --}}
            <div class="flex items-center gap-2.5">
                <svg class="h-4 w-4 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l3.09 6.26L21 9.27l-5 4.67 1.18 6.88L12 17.77l-6.18 3.25L7 14.27 3 9.27l5.91-1.51z"/></svg>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 font-medium text-rose-700 ring-1 ring-rose-100">
                    <span class="font-bold">3000+</span> vendor pernikahan terpercaya di Indonesia
                </span>
            </div>

            {{-- Auth CTA area --}}
            <div class="flex items-center gap-3 text-ink-600 uppercase tracking-[0.08em]">
                @auth
                    <span class="normal-case tracking-normal text-ink-700 font-semibold">{{ auth()->user()->name }}</span>
                    @if (auth()->user()->isCouple())
                        <a href="{{ route('my-bookings.index') }}" class="text-rose-600 hover:text-rose-700 transition-colors">Booking Saya</a>
                    @endif
                    <form method="POST" action="{{ route('frontend.login.logout') }}">@csrf<button class="text-rose-600 hover:text-rose-700 transition-colors">Keluar</button></form>
                @else
                    {{-- Login / Register buttons --}}
                    <a href="{{ route('frontend.login.create') }}" class="bd-nav-btn-secondary">Masuk</a>
                    <a href="{{ route('frontend.register.create') }}" class="bd-nav-btn-primary">Daftar</a>
                    {{-- Vendor onboarding CTA — visually distinct --}}
                    @if (! auth()->check() || auth()->user()->isCouple())
                        <span class="text-ink-300">|</span>
                        <a href="{{ route('vendors.register.create') }}" class="inline-flex items-center gap-1.5 rounded-full border border-gold-400/60 bg-gradient-to-r from-gold-400 to-gold-500 px-3.5 py-1.5 text-xs font-bold text-ink-900 shadow-sm transition-all duration-300 hover:from-gold-500 hover:to-gold-600 hover:shadow-md">
                            <svg class="-ml-0.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2 2M8 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z"/></svg>
                            Jadi Mitra Vendor
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Main header bar (logo + search + actions) --}}
    <div class="bd-container bd-navbar-main flex items-center justify-between gap-4 px-6 py-4 lg:py-[1.15rem]">
        {{-- Logo --}}
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
            <a href="{{ route('vendors.index') }}" class="bd-btn-primary hidden text-sm px-5 py-2.5 sm:inline-flex">
                Jelajahi Vendor
            </a>
            <button data-menu-toggle aria-label="Menu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200/60 hover:border-rose-400/50 hover:bg-rose-50 transition-all duration-300 lg:hidden">
                <svg class="h-5 w-5 text-ink-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile search --}}
    <form method="GET" action="{{ route('vendors.index') }}" class="relative pb-3 md:hidden">
        <div class="bd-container px-6">
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari vendor pernikahanmu di sini"
                       class="w-full rounded-full border border-ink-200/80 bg-white py-2.5 pl-11 pr-4 text-sm text-ink-700 shadow-sm outline-none transition-all duration-300 placeholder:text-ink-400 focus:border-rose-300 focus:ring-4 focus:ring-rose-600/10">
            </div>
        </div>
    </form>

    {{-- Category navigation (desktop only) — inline first 5 + "Lainnya" dropdown --}}
    <nav class="navbar-catnav hidden border-t border-ink-200/60 bg-white lg:block">
        <div class="bd-container px-6">
            <div class="bd-catnav flex items-center gap-1 py-1 overflow-visible">
                <a href="{{ route('home') }}"
                   class="bd-catnav-item flex-shrink-0 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-600 transition-all duration-300 {{ request()->routeIs('home') ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                    Home
                </a>
                <a href="{{ route('vendors.index') }}"
                   class="bd-catnav-item flex-shrink-0 whitespace-nowrap px-4 py-2 text-sm font-semibold text-ink-600 transition-all duration-300 {{ request()->routeIs('vendors.index') && ! $activeCategory ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                    Semua Vendor
                </a>
                @foreach ($inlineCategories as $cat)
                    <a href="{{ route('vendors.category', $cat->slug) }}"
                       class="bd-catnav-item flex-shrink-0 inline-flex items-center gap-1.5 whitespace-nowrap px-4 py-2 text-sm font-medium text-ink-600 transition-all duration-300 {{ $activeCategory === $cat->slug ? 'active' : 'hover:text-rose-600 hover:bg-rose-50 rounded-full' }}">
                        <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-4 w-4 shrink-0 {{ $activeCategory === $cat->slug ? 'text-white' : 'text-rose-500' }}"/>
                        {{ $cat->name }}
                    </a>
                @endforeach

                {{-- "Lainnya" dropdown for overflow categories --}}
                @if ($overflowCategories->isNotEmpty())
                    <div class="bd-catnav-dropdown relative flex-shrink-0" data-dropdown>
                        <button type="button" data-dropdown-toggle aria-expanded="false"
                                class="bd-catnav-item flex items-center gap-1.5 whitespace-nowrap px-4 py-2 text-sm font-medium text-ink-600 transition-all duration-300 hover:text-rose-600 hover:bg-rose-50 rounded-full">
                            Lainnya
                            <svg class="h-3.5 w-3.5 transition-transform duration-200" data-dropdown-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div data-dropdown-menu
                             class="invisible absolute left-0 top-full z-[110] mt-1.5 min-w-[220px] origin-top-left rounded-xl border border-ink-200/70 bg-white/95 p-1.5 opacity-0 shadow-xl shadow-ink-900/10 backdrop-blur-xl transition-all duration-200">
                            @foreach ($overflowCategories as $cat)
                                <a href="{{ route('vendors.category', $cat->slug) }}"
                                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium {{ $activeCategory === $cat->slug ? 'bg-rose-50 text-rose-700' : 'text-ink-600 hover:bg-rose-50 hover:text-rose-600' }} transition-colors">
                                    <x-frontend.category-icon :name="$cat->name" :slug="$cat->slug" class="h-4 w-4 shrink-0 text-rose-500"/>
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Mobile menu panel --}}
    <div data-mobile-menu class="hidden border-t border-ink-200/60 bg-white/95 backdrop-blur-sm">
        <div class="bd-container flex flex-col gap-1.5 px-6 py-4">
            <a href="{{ route('home') }}" class="block rounded-md px-4 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Home</a>
            <a href="{{ route('vendors.index') }}" class="block rounded-md px-4 py-2.5 text-sm font-bold text-ink-900 hover:bg-rose-50 transition-colors">Semua Vendor</a>
            @foreach ($navCategories as $cat)
                <a href="{{ route('vendors.category', $cat->slug) }}" class="block rounded-md px-4 py-2.5 text-sm text-ink-600 hover:bg-rose-50 hover:text-rose-600 transition-colors">{{ $cat->name }}</a>
            @endforeach
            <div class="pt-3">
                <a href="{{ route('vendors.index') }}" class="bd-btn-primary w-full justify-center text-sm">Jelajahi Vendor</a>
            </div>
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
