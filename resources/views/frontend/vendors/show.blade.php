@extends('frontend.layouts.app')

@section('title', $vendor->business_name . ' — BrightDor')

@section('content')
    <div class="bd-container py-8">
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-[5px] border border-emerald-500/40 bg-emerald-50 p-4 text-sm text-emerald-800" data-booking-success>
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <p class="flex-1 font-medium">{{ session('success') }}</p>
                <button type="button" class="text-emerald-600 hover:text-emerald-800" data-booking-success-close>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-ink-400" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-rose-600 transition-colors">Beranda</a>
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('vendors.index') }}" class="hover:text-rose-600 transition-colors">Vendor</a>
            @if ($vendor->category)
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('vendors.category', $vendor->category->slug) }}" class="hover:text-rose-600 transition-colors">{{ $vendor->category->name }}</a>
            @endif
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-ink-700">{{ $vendor->business_name }}</span>
        </nav>

        {{-- Header --}}
        <div class="mt-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($vendor->is_verified)
                        <span class="bd-badge bd-badge-gold flex items-center gap-1">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            Verified
                        </span>
                    @endif
                    @if ($vendor->is_featured)
                        <span class="bd-badge bd-badge-ink flex items-center gap-1">Vendor Unggulan</span>
                    @endif
                </div>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">{{ $vendor->business_name }}</h1>

                <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-ink-500">
                    <span class="inline-flex items-center gap-1.5">
                        <x-frontend.category-icon :name="$vendor->category?->name" :slug="$vendor->category?->slug" class="h-4 w-4 text-rose-600"/>
                        {{ $vendor->category?->name ?? 'Vendor' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 0c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        {{ $vendor->city }}@if ($vendor->province), {{ $vendor->province }}@endif
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-frontend.rating-stars :rating="$vendor->rating_avg" size="sm"/>
                        <span class="font-bold text-ink-900">{{ number_format((float) $vendor->rating_avg, 1) }}</span>
                        <span class="text-ink-400">({{ $vendor->rating_count }} ulasan)</span>
                    </span>
                </div>
            </div>

            {{-- Sticky-ish CTA (desktop) --}}
            <div class="hidden flex-col gap-2.5 md:flex">
                <a href="{{ $vendor->whatsapp ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $vendor->whatsapp) : '#' }}"
                   target="_blank" rel="noopener"
                   class="bd-btn-primary">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm5.83 14.12c-.25.7-1.45 1.33-2.02 1.42-.52.08-1.17.11-1.88-.12-.44-.14-1-.32-1.71-.63-3-1.3-4.96-4.32-5.11-4.52-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.19 1.05-2.49.27-.3.6-.37.8-.37h.57c.18.01.43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.15.17-.32.39-.46.52-.15.15-.31.31-.13.61.18.3.79 1.3 1.7 2.11 1.16 1.04 2.14 1.36 2.44 1.51.3.15.48.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.68-.15.27.1 1.75.83 2.05.98.3.15.5.22.57.35.08.12.08.72-.17 1.42Z"/></svg>
                    Hubungi Vendor
                </a>
                <a href="#pricing" class="bd-btn-secondary">
                    <x-frontend.ring-icon class="h-4 w-4"/>
                    Ajukan Penawaran
                </a>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_360px]">
            {{-- Left column --}}
            <div class="space-y-10">
                {{-- Gallery --}}
                <section>
                    @php
                        $images = $portfolio->map(fn ($m) => $m->getUrl())->concat($gallery->pluck('image')->filter())->values();
                        $hasReal = $images->isNotEmpty();
                        $mainSrc = $hasReal ? $images->first() : null;
                    @endphp
                    <div class="overflow-hidden rounded-[5px] shadow-[0_1px_2px_rgba(0,0,0,0.02),_0_12px_32px_rgba(0,0,0,0.04)]">
                        <x-frontend.cover :src="$mainSrc" :title="$vendor->business_name" :category="$vendor->category?->name" class="aspect-[16/9]"/>
                    </div>
                    <div class="mt-3 grid grid-cols-4 gap-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="overflow-hidden rounded-[5px] shadow-[0_1px_2px_rgba(0,0,0,0.02),_0_8px_24px_rgba(0,0,0,0.03)]">
                                <x-frontend.cover :src="$hasReal && $images->has($i) ? $images->get($i) : null"
                                                  :title="$vendor->business_name" :category="$i === 4 ? 'Lainnya' : ($vendor->category?->name ?? '')"
                                                  class="aspect-square"/>
                            </div>
                        @endfor
                    </div>
                </section>

                {{-- Description --}}
                <section class="bd-card p-6 sm:p-8">
                    <h2 class="flex items-center gap-2 font-display text-2xl font-bold text-ink-900">
                        <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.04c2.61-1.53 6.5-.67 7.5 2.68 1.13 3.8-2.25 7.06-4.5 9.06-1.2 1.07-2.4 2.22-3 2.22s-1.8-1.15-3-2.22c-2.25-2-5.63-5.26-4.5-9.06 1-3.35 4.89-4.21 7.5-2.68Z"/></svg>
                        Tentang Vendor
                    </h2>
                    <div class="mt-4 text-[15px] leading-relaxed text-ink-500">
                        {{ $vendor->description ?: 'Belum ada deskripsi dari vendor ini.' }}
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="flex items-start gap-3 rounded-[5px] bg-ink-50 p-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-rose-600 ring-1 ring-ink-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 0c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </span>
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-ink-400 font-bold">Alamat</p>
                                <p class="mt-0.5 text-sm text-ink-600">{{ $vendor->address ?: 'Alamat belum tersedia' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-[5px] bg-ink-50 p-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-rose-600 ring-1 ring-ink-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.28 6.72 15 15 15h3.75v-5.25l-3.75-1.5-1.5 1.5a11.98 11.98 0 0 1-6-6l1.5-1.5-1.5-3.75H2.25Z"/></svg>
                            </span>
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-ink-400 font-bold">Telepon / WhatsApp</p>
                                <p class="mt-0.5 text-sm text-ink-600">{{ $vendor->whatsapp ?: $vendor->phone ?: 'Belum tersedia' }}</p>
                            </div>
                        </div>
                        @if ($vendor->instagram)
                            <div class="flex items-start gap-3 rounded-[5px] bg-ink-50 p-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-rose-600 ring-1 ring-ink-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8 0 3.2 0 3.6-.1 4.8-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1-3.2 0-3.6 0-4.8-.1-3.3-.1-4.8-1.7-4.9-4.9-.1-1.3-.1-1.6-.1-4.8 0-3.2 0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2Zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4Zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.4-10.4a1.4 1.4 0 1 1-2.8 0 1.4 1.4 0 0 1 2.8 0Z"/></svg>
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wider text-ink-400 font-bold">Instagram</p>
                                    <a href="{{ 'https://instagram.com/' . ltrim($vendor->instagram, '@') }}" target="_blank" rel="noopener" class="mt-0.5 block text-sm text-rose-600 hover:text-rose-700 transition-colors">{{ $vendor->instagram }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($vendor->website)
                            <div class="flex items-start gap-3 rounded-[5px] bg-ink-50 p-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-rose-600 ring-1 ring-ink-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0c2.5-2.5 3.5-6.5 3.5-9S14.5 5.5 12 3m0 18c-2.5-2.5-3.5-6.5-3.5-9S9.5 5.5 12 3"/></svg>
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wider text-ink-400 font-bold">Website</p>
                                    <a href="{{ \Illuminate\Support\Str::startsWith($vendor->website, 'http') ? $vendor->website : 'https://' . $vendor->website }}" target="_blank" rel="noopener" class="mt-0.5 block text-sm text-rose-600 hover:text-rose-700 transition-colors">{{ $vendor->website }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Packages --}}
                <section id="pricing" class="scroll-mt-32 bd-card p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center gap-2 font-display text-2xl font-bold text-ink-900">
                            <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Z"/></svg>
                            Daftar Paket Harga
                        </h2>
                        <span class="text-xs text-ink-400 font-medium">{{ $vendor->services->count() }} paket</span>
                    </div>

                    @if ($vendor->services->isNotEmpty())
                        <div class="mt-6 space-y-4">
                            @foreach ($vendor->services as $service)
                                <div class="group rounded-[5px] border border-ink-200 p-5 transition-all duration-300 hover:border-rose-400/50 hover:bg-rose-50/40 hover:shadow-md">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="font-display text-lg font-bold text-ink-900">{{ $service->name }}</h3>
                                            <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink-400">
                                                @if ($service->capacity)
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                                        Kapasitas {{ $service->capacity }} tamu
                                                    </span>
                                                @endif
                                                @if ($service->duration)
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                        {{ $service->duration }}
                                                    </span>
                                                @endif
                                                <span>{{ $service->price_unit ?? 'per event' }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            @if ($service->discount_price)
                                                <p class="text-xs text-ink-400 line-through">{{ rupiah($service->price) }}</p>
                                                <p class="font-display text-xl font-bold text-rose-600">{{ rupiah($service->discount_price) }}</p>
                                            @else
                                                <p class="font-display text-xl font-bold text-ink-900">{{ rupiah($service->price) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($service->description)
                                        <p class="mt-3 text-sm leading-relaxed text-ink-500">{{ $service->description }}</p>
                                    @endif

                                    @if (! empty($service->features))
                                        <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                                            @foreach ($service->features as $feature)
                                                <li class="flex items-center gap-2 text-sm text-ink-600">
                                                    <svg class="h-4 w-4 shrink-0 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-5 rounded-[5px] bg-ink-100 p-4 text-sm text-ink-500">Belum ada paket harga yang dipublikasikan. Hubungi vendor untuk penawaran khusus.</p>
                    @endif
                </section>

                {{-- Reviews --}}
                <section class="bd-card p-6 sm:p-8">
                    <h2 class="flex items-center gap-2 font-display text-2xl font-bold text-ink-900">
                        <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 6L5.4 15.38a2.25 2.25 0 0 1 1.6-.63h12.5a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v12.75Z"/></svg>
                        Review &amp; Rating
                    </h2>

                    <div class="mt-6 flex items-center gap-6 rounded-[5px] bg-ink-50 p-5 sm:p-6">
                        <div class="text-center">
                            <p class="font-display text-5xl font-extrabold text-ink-900">{{ number_format((float) $vendor->rating_avg, 1) }}</p>
                            <x-frontend.rating-stars :rating="$vendor->rating_avg" size="md" class="mt-1 justify-center"/>
                            <p class="mt-1 text-xs text-ink-400">{{ $vendor->rating_count }} ulasan</p>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            @for ($bar = 5; $bar >= 1; $bar--)
                                @php
                                    $pct = $vendor->rating_count > 0 ? max(8, round((6 - $bar) * 18)) : 0;
                                @endphp
                                <div class="flex items-center gap-2 text-xs text-ink-400">
                                    <span class="w-3 font-semibold">{{ $bar }}</span>
                                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-ink-200">
                                        <div class="h-full rounded-full bg-rose-500 transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    @if ($reviews->isNotEmpty())
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($reviews as $review)
                                <div class="rounded-[5px] border border-ink-200 p-5 transition-all duration-300 hover:border-rose-400/40 hover:shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-600 font-display text-sm font-bold text-white ring-1 ring-rose-600/20">
                                            {{ mb_strtoupper(mb_substr($review->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-bold text-ink-900">{{ $review->name }}</p>
                                            <p class="text-xs text-ink-400">{{ $review->role }}</p>
                                        </div>
                                        <div class="ml-auto"><x-frontend.rating-stars :rating="$review->rating" size="sm"/></div>
                                    </div>
                                    <p class="mt-3 text-sm leading-relaxed text-ink-500">"{{ $review->content }}"</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-5 text-sm text-ink-500">Belum ada review untuk vendor ini. Jadilah yang pertama memberi ulasan!</p>
                    @endif
                </section>
            </div>

            {{-- Right sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-32 lg:self-start">
                <div class="bd-card p-6">
                    <h3 class="font-display text-xl font-bold text-ink-900">Mulai dari</h3>
                    @php
                        $starting = $vendor->services->min(fn ($s) => (float) $s->final_price);
                    @endphp
                    <p class="mt-1 font-display text-3xl font-extrabold text-rose-600">{{ rupiah($starting) }}</p>
                    <p class="mt-1 text-xs text-ink-400">per {{ $vendor->services->first()?->price_unit ?? 'event' }}</p>

                    <div class="mt-6 space-y-3">
                        <a href="{{ $vendor->whatsapp ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $vendor->whatsapp) : '#' }}"
                           target="_blank" rel="noopener"
                           class="bd-btn-primary w-full justify-center">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm5.83 14.12c-.25.7-1.45 1.33-2.02 1.42-.52.08-1.17.11-1.88-.12-.44-.14-1-.32-1.71-.63-3-1.3-4.96-4.32-5.11-4.52-.15-.2-1.22-1.62-1.22-3.1 0-1.47.77-2.19 1.05-2.49.27-.3.6-.37.8-.37h.57c.18.01.43-.07.67.51.25.6.85 2.07.92 2.22.08.15.13.33.03.53-.1.2-.15.32-.3.5-.15.17-.32.39-.46.52-.15.15-.31.31-.13.61.18.3.79 1.3 1.7 2.11 1.16 1.04 2.14 1.36 2.44 1.51.3.15.48.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.68-.15.27.1 1.75.83 2.05.98.3.15.5.22.57.35.08.12.08.72-.17 1.42Z"/></svg>
                            Hubungi Vendor
                        </a>
                        <a href="#pricing" class="bd-btn-secondary w-full justify-center">
                            <x-frontend.ring-icon class="h-4 w-4"/>
                            Ajukan Penawaran
                        </a>
                        <button data-booking-open class="bd-btn-ghost w-full justify-center ring-1 ring-ink-200 hover:ring-rose-400/50">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M4.5 5.25h15a.75.75 0 0 1 .75.75v13.5a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V6a.75.75 0 0 1 .75-.75ZM12 13.5h.008v.008H12V13.5Zm0 3h.008v.008H12V16.5Zm-3-3h.008v.008H9V13.5Zm0 3h.008v.008H9V16.5Zm6-3h.008v.008H15V13.5Z"/></svg>
                            Booking Tanggal
                        </button>
                    </div>

                    <div class="mt-6 space-y-3.5 pt-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-50 text-rose-600 ring-1 ring-ink-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 0c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </span>
                            <span class="text-sm text-ink-600">{{ $vendor->address ?: $vendor->city }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-50 text-rose-600 ring-1 ring-ink-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.28 6.72 15 15 15h3.75v-5.25l-3.75-1.5-1.5 1.5a11.98 11.98 0 0 1-6-6l1.5-1.5-1.5-3.75H2.25Z"/></svg>
                            </span>
                            <span class="text-sm text-ink-600">{{ $vendor->whatsapp ?: $vendor->phone ?: '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-50 text-rose-600 ring-1 ring-ink-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </span>
                            <span class="text-sm text-ink-600">Respon cepat &lt; 1 jam</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-[5px] border border-ink-200/70 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.02),_0_12px_32px_rgba(0,0,0,0.04)]">
                    <div class="h-44 bg-gradient-to-br from-rose-700 via-rose-500 to-rose-300 relative">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full border border-white/20"></div>
                        <div class="absolute -left-4 -bottom-4 h-20 w-20 rounded-full border border-white/15"></div>
                        <span class="absolute inset-0 flex items-center justify-center gap-1.5 text-[11px] uppercase tracking-[0.25em] text-white/80 font-bold">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 0c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Lokasi Vendor
                        </span>
                    </div>
                    <div class="p-5 text-sm text-ink-500">
                        <p class="font-bold text-ink-900">{{ $vendor->business_name }}</p>
                        <p class="mt-1">{{ $vendor->address }}, {{ $vendor->city }}, {{ $vendor->province }}</p>
                        <a href="{{ 'https://www.google.com/maps/search/?api=1&query=' . urlencode(implode(', ', array_filter([$vendor->address, $vendor->city, $vendor->province]))) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                            Buka di Google Maps
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Similar vendors --}}
        @if ($similarVendors->isNotEmpty())
            <section class="mt-16">
                <h2 class="bd-section-title">Vendor Serupa Lainnya</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($similarVendors as $similar)
                        <x-frontend.vendor-card :vendor="$similar"/>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Booking modal --}}
    <div data-booking-modal class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-ink-900/50 backdrop-blur-sm" data-booking-close></div>
        <div class="relative w-full max-w-md rounded-[5px] bg-white p-6 shadow-2xl shadow-ink-900/20">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-xl font-bold text-ink-900">Booking Tanggal</h3>
                <button data-booking-close class="flex h-9 w-9 items-center justify-center rounded-full hover:bg-ink-50 transition-colors">
                    <svg class="h-4 w-4 text-ink-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="mt-1 text-sm text-ink-500">Pilih paket dan tanggal acara kamu, lalu kirimkan ke {{ $vendor->business_name }}.</p>

            <form method="POST" action="{{ route('vendors.booking', $vendor->slug) }}" class="mt-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="booking-name" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nama Lengkap</label>
                        <input id="booking-name" type="text" name="name" value="{{ old('name') }}" required class="bd-input mt-1.5">
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="booking-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                        <input id="booking-email" type="email" name="email" value="{{ old('email') }}" required class="bd-input mt-1.5">
                        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="booking-phone" class="text-xs font-bold uppercase tracking-wider text-ink-400">No. HP / WhatsApp</label>
                    <input id="booking-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="cth. 0812xxxxxxx" required class="bd-input mt-1.5">
                    @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="booking-service" class="text-xs font-bold uppercase tracking-wider text-ink-400">Pilih Paket</label>
                    <select id="booking-service" name="service_id" class="bd-input mt-1.5" required>
                        @foreach ($vendor->services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }} — {{ rupiah($service->final_price) }}</option>
                        @endforeach
                    </select>
                    @error('service_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="booking-date" class="text-xs font-bold uppercase tracking-wider text-ink-400">Tanggal Acara</label>
                        <input id="booking-date" type="date" name="event_date" value="{{ old('event_date') }}" min="{{ now()->toDateString() }}" class="bd-input mt-1.5">
                        @error('event_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="booking-guests" class="text-xs font-bold uppercase tracking-wider text-ink-400">Jumlah Tamu</label>
                        <input id="booking-guests" type="number" name="guest_count" value="{{ old('guest_count') }}" placeholder="cth. 300" min="1" class="bd-input mt-1.5">
                        @error('guest_count')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="booking-notes" class="text-xs font-bold uppercase tracking-wider text-ink-400">Catatan</label>
                    <textarea id="booking-notes" name="customer_notes" rows="3" placeholder="Ceritakan kebutuhanmu..." class="bd-input mt-1.5 resize-none">{{ old('customer_notes') }}</textarea>
                    @error('customer_notes')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                @if ($errors->any())
                    <p class="rounded-[5px] bg-rose-50 px-4 py-3 text-sm text-rose-600 ring-1 ring-rose-500/30">Periksa kembali isian kamu, ada beberapa kolom yang belum benar.</p>
                @endif
                <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                    Kirim Permintaan Booking
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const modal = document.querySelector('[data-booking-modal]');
        document.querySelectorAll('[data-booking-open]').forEach(btn => {
            btn.addEventListener('click', () => {
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            });
        });
        document.querySelectorAll('[data-booking-close]').forEach(el => {
            el.addEventListener('click', () => {
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            });
        });
        document.querySelectorAll('[data-booking-success-close]').forEach(btn => {
            btn.addEventListener('click', () => btn.closest('[data-booking-success]')?.remove());
        });
    </script>
@endsection
