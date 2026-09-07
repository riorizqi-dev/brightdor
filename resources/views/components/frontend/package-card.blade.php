@props([
    'service' => null,
])

@php
    $vendor = $service->vendor;
    $category = $service->category ?? $vendor?->category;
    $coverUrl = $service->getFirstMediaUrl('cover') ?: ($vendor?->getFirstMediaUrl('portfolio') ?: '');
    $finalPrice = $service->final_price;
    $hasDiscount = $service->discount_price && $service->discount_price < $service->price;
    $discountPercent = $hasDiscount ? round((($service->price - $service->discount_price) / $service->price) * 100) : 0;
    $rawUnit = $service->price_unit ?? 'event';
    $cleanUnit = str_starts_with(strtolower(trim($rawUnit)), 'per ') ? $rawUnit : 'per ' . $rawUnit;
@endphp

<article class="group bd-vendor-card flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    {{-- Cover & Badges --}}
    <div class="relative overflow-hidden aspect-[16/10] bg-rose-50">
        <x-frontend.cover :src="$coverUrl" :title="$service->name" :category="$category?->name"
                          class="h-full w-full object-cover transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"/>

        {{-- Badges Top --}}
        <div class="absolute left-3 top-3 flex flex-wrap items-center gap-1.5">
            @if ($service->is_featured || $service->bookings_count >= 10)
                <span class="inline-flex items-center gap-1 rounded-md bg-rose-600/95 backdrop-blur-xs px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-white shadow-xs">
                    <svg class="h-3 w-3 text-amber-300" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    Populer
                </span>
            @endif

            @if ($hasDiscount)
                <span class="inline-flex items-center gap-1 rounded-md bg-emerald-600/95 backdrop-blur-xs px-2 py-1 text-[11px] font-extrabold text-white shadow-xs">
                    Hemat {{ $discountPercent }}%
                </span>
            @endif
        </div>

        {{-- Category Pill Bottom --}}
        @if ($category)
            <div class="absolute bottom-3 left-3">
                <span class="inline-flex items-center gap-1.5 rounded-md bg-white/95 backdrop-blur-md px-2.5 py-1 text-xs font-bold text-ink-800 shadow-xs ring-1 ring-black/5">
                    <x-frontend.category-icon :name="$category->name" :slug="$category->slug" class="h-3.5 w-3.5 text-rose-600"/>
                    {{ $category->name }}
                </span>
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="flex flex-1 flex-col p-5 sm:p-6 justify-between">
        <div>
            {{-- Vendor identity --}}
            @if ($vendor)
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-100 text-[10px] font-bold text-rose-700">
                        {{ mb_substr($vendor->business_name, 0, 1) }}
                    </span>
                    <a href="{{ route('vendors.show', $vendor->slug) }}" class="text-xs font-bold text-ink-500 hover:text-rose-600 transition-colors truncate">
                        {{ $vendor->business_name }}
                    </a>
                    @if ($vendor->city)
                        <span class="text-xs text-ink-400">• {{ $vendor->city }}</span>
                    @endif
                </div>
            @endif

            {{-- Package Title --}}
            <h3 class="font-display text-lg font-bold text-ink-900 line-clamp-2 group-hover:text-rose-600 transition-colors leading-snug">
                <a href="{{ route('vendors.show', $vendor?->slug ?? '') . '#pricing' }}">
                    {{ $service->name }}
                </a>
            </h3>

            {{-- Specs (Capacity & Duration) --}}
            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-ink-500 font-medium">
                @if ($service->capacity)
                    <span class="inline-flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                        Kapasitas {{ $service->capacity }} tamu
                    </span>
                @endif
                @if ($service->duration)
                    <span class="inline-flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        {{ $service->duration }}
                    </span>
                @endif
                @if ($service->bookings_count > 0)
                    <span class="inline-flex items-center gap-1 text-rose-600 font-semibold">
                        {{ $service->bookings_count }}+ dipesan
                    </span>
                @endif
            </div>

            {{-- Key Feature Highlights --}}
            @if (! empty($service->features) && is_array($service->features))
                <ul class="mt-3.5 space-y-1.5 border-t border-rose-100/60 pt-3">
                    @foreach (array_slice($service->features, 0, 2) as $feat)
                        <li class="flex items-start gap-2 text-xs text-ink-600">
                            <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            <span class="line-clamp-1">{{ $feat }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Price & Action Button --}}
        <div class="mt-5 border-t border-rose-100/80 pt-4 flex items-end justify-between gap-3">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-ink-400">Harga Paket</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <p class="font-display text-xl font-extrabold text-rose-600">{{ rupiah($finalPrice) }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    @if ($hasDiscount)
                        <span class="text-xs text-ink-400 line-through">{{ rupiah($service->price) }}</span>
                    @endif
                    <span class="text-xs text-ink-500 font-medium">{{ $cleanUnit }}</span>
                </div>
            </div>

            <a href="{{ route('vendors.show', $vendor?->slug ?? '') . '#pricing' }}"
               class="bd-btn-primary shrink-0 py-2.5 px-4 text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-1.5">
                <span>Pilih Paket</span>
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</article>
