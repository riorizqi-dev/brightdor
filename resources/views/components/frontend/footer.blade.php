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
                    Marketplace vendor pernikahan premium di Indonesia. Temukan venue, katering, dekorasi, dan ribuan vendor terpercaya untuk hari bahagiamu.
                </p>
                <div class="mt-6 flex items-center gap-2">
                    @foreach (['instagram', 'tiktok', 'facebook'] as $social)
                        <a href="#" aria-label="{{ $social }}"
                           class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-ink-300 transition-all duration-300 hover:border-rose-500 hover:text-white hover:bg-rose-600 hover:-translate-y-0.5 hover:shadow-md">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                @if ($social === 'instagram')
                                    <path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8 0 3.2 0 3.6-.1 4.8-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1-3.2 0-3.6 0-4.8-.1-3.3-.1-4.8-1.7-4.9-4.9-.1-1.3-.1-1.6-.1-4.8 0-3.2 0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2Zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4Zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.4-10.4a1.4 1.4 0 1 1-2.8 0 1.4 1.4 0 0 1 2.8 0Z"/>
                                @elseif ($social === 'tiktok')
                                    <path d="M19.6 6.7a5.4 5.4 0 0 1-3.4-1.2 5.4 5.4 0 0 1-2-3.4h-3.4v13.4a2.9 2.9 0 1 1-2.9-2.9c.3 0 .6 0 .9.1V9.2a6.4 6.4 0 0 0-.9-.1 6.3 6.3 0 1 0 6.3 6.3V9.6a8.7 8.7 0 0 0 5.4 1.9V8.1c-.1 0-.2 0-.2-.1V6.7Z"/>
                                @else
                                    <path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.7 4.53-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.89v2.26h3.32l-.53 3.49h-2.79V24C19.61 23.1 24 18.1 24 12.07Z"/>
                                @endif
                            </svg>
                        </a>
                    @endforeach
                </div>
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
                    <li><a href="{{ route('vendors.index', ['sort' => 'rating']) }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Vendor Terbaik</a></li>
                    <li><a href="{{ route('vendors.index', ['sort' => 'popular']) }}" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Vendor Populer</a></li>
                    <li><a href="#" class="transition-colors duration-300 hover:text-rose-400 hover:pl-0.5">Inspirasi Pernikahan</a></li>
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
            <p>Premium Wedding Marketplace Indonesia</p>
        </div>
    </div>
</footer>
