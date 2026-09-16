@php
    $footerCategories = \App\Models\VendorCategory::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();
@endphp

<footer class="mt-auto border-t border-ink-200/70 bg-ink-900 text-ink-300">
    <div class="bd-container py-16">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-600 text-white ring-1 ring-white/20 shadow-md transition group-hover:shadow-lg group-hover:-translate-y-0.5 duration-300">
                        <x-frontend.ring-icon class="h-5 w-5"/>
                    </span>
                    <span class="flex flex-col leading-none">
                        <span class="font-display text-2xl font-extrabold text-white">Bright<span class="text-rose-400">Dor</span></span>
                        <span class="mt-0.5 text-[9px] uppercase tracking-[0.22em] text-rose-400 font-bold">Premier Wedding</span>
                    </span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-300">
                    Marketplace vendor pernikahan di Indonesia. Temukan venue, katering, dekorasi, dan vendor terverifikasi lainnya, lalu booking dengan pembayaran rekber yang aman.
                </p>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Kategori Vendor</h4>
                <ul class="mt-5 space-y-3 text-sm text-ink-300">
                    @foreach ($footerCategories->take(6) as $cat)
                        <li><a href="{{ route('vendors.category', $cat->slug) }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Jelajahi</h4>
                <ul class="mt-5 space-y-3 text-sm text-ink-300">
                    <li><a href="{{ route('vendors.index') }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Semua Vendor</a></li>
                    <li><a href="{{ route('vendors.index', ['sort' => 'rating']) }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Rating Tertinggi</a></li>
                    <li><a href="{{ route('packages.index') }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Paket Populer</a></li>
                    <li><a href="{{ route('vendors.register.create') }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Jadi Mitra Vendor</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white">Hubungi Kami</h4>
                <ul class="mt-5 space-y-4 text-sm text-ink-300">
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-rose-400 ring-1 ring-white/10">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25 11 3l10 2.25V18.75L13 21 3 18.75V5.25ZM7 8h4v13M17 8h3v13"/></svg>
                        </span>
                        hello@brightdor.id
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-rose-400 ring-1 ring-white/10">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.28 6.72 15 15 15h3.75v-5.25l-3.75-1.5-1.5 1.5a11.98 11.98 0 0 1-6-6l1.5-1.5-1.5-3.75H2.25Z"/></svg>
                        </span>
                        021-1234-5678
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-rose-400 ring-1 ring-white/10">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 0c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        </span>
                        Jakarta, Indonesia
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-14 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-7 text-xs text-ink-400 sm:flex-row">
            <p>&copy; {{ date('Y') }} BrightDor. Seluruh hak cipta dilindungi.</p>
            <p>Marketplace Vendor Pernikahan Indonesia</p>
        </div>
    </div>
</footer>
