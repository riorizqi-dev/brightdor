@props([
    'vendor' => null,
])

@php
    $category = $vendor->category;
    $startingPrice = $vendor->services?->filter(fn ($s) => $s->status === 'published' && $s->is_active)
        ->min(fn ($s) => (float) $s->final_price);

    // Real product cover: vendor portfolio -> first published service cover -> any published service cover.
    // Only falls back to initials (inside <x-frontend.cover>) when truly no image exists.
    $coverUrl = $vendor->getFirstMediaUrl('portfolio');

    if (blank($coverUrl) && $vendor->relationLoaded('services')) {
        $published = $vendor->services->filter(fn ($s) => $s->status === 'published' && $s->is_active);
        foreach ($published as $svc) {
            $candidate = $svc->getFirstMediaUrl('cover');
            if (filled($candidate)) {
                $coverUrl = $candidate;
                break;
            }
        }
    } elseif (blank($coverUrl)) {
        // Relation not eager-loaded (e.g. direct query): lazy lookup without N+1 for the single card case.
        $coverUrl = $vendor->services()
            ->where('status', 'published')
            ->where('is_active', true)
            ->get()
            ->first(fn ($svc) => filled($svc->getFirstMediaUrl('cover')))
            ?->getFirstMediaUrl('cover') ?? '';
    }
@endphp

<a href="{{ route('vendors.show', $vendor->slug) }}"
   class="group flex flex-col overflow-hidden rounded-[5px] bg-white ring-1 ring-black/5 shadow-[0_1px_2px_rgba(0,0,0,0.02),_0_8px_24px_rgba(0,0,0,0.04)] transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_4px_8px_rgba(0,0,0,0.04),_0_20px_44px_rgba(0,0,0,0.09)]"
   wire:navigate>
    <div class="relative overflow-hidden">
        <x-frontend.cover :src="$coverUrl" :title="$vendor->business_name" :category="$category?->name"
                          class="aspect-[4/3] transition duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-[1.04]"/>

        <div class="absolute left-3 top-3 flex items-center gap-2">
            @if ($vendor->is_featured)
                <span class="inline-flex items-center gap-1 rounded-[4px] bg-rose-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm ring-1 ring-white/20">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    Unggulan
                </span>
            @endif
            @if ($vendor->is_verified)
                <span class="inline-flex items-center gap-1 rounded-[4px] bg-white px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-rose-600 shadow-sm ring-1 ring-black/10">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                    Verified
                </span>
            @endif
        </div>

        @if ($category)
            <span class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 rounded-[4px] bg-white px-2.5 py-1.5 text-[11px] font-semibold text-ink-600 shadow-sm ring-1 ring-black/5">
                <x-frontend.category-icon :name="$category->name" :slug="$category->slug" class="h-3.5 w-3.5 text-rose-600"/>
                {{ $category->name }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-lg font-bold leading-snug text-ink-900 transition duration-300 group-hover:text-rose-600">
            {{ $vendor->business_name }}
        </h3>

        <div class="mt-1.5 flex items-center gap-1.5 text-sm text-ink-500">
            <svg class="h-4 w-4 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
            </svg>
            {{ $vendor->city ?? 'Indonesia' }}
        </div>

        <div class="mt-2 flex items-center gap-1.5">
            <x-frontend.rating-stars :rating="$vendor->rating_avg" size="sm"/>
            <span class="text-sm font-bold text-ink-900">{{ number_format((float) $vendor->rating_avg, 1) }}</span>
            <span class="text-xs text-ink-400">({{ $vendor->rating_count }} ulasan)</span>
        </div>

        <div class="mt-auto pt-4">
            <div class="bd-divider"></div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-ink-400 font-bold">Mulai dari</p>
                    <p class="font-display text-lg font-bold text-ink-900 mt-0.5">
                        {{ rupiah($startingPrice, true) }}
                    </p>
                </div>

                <span class="inline-flex items-center gap-1 rounded-[4px] border border-rose-500/40 bg-rose-50 px-3.5 py-1.5 text-xs font-bold text-rose-600 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:border-rose-600 group-hover:shadow-md group-hover:translate-x-0.5">
                    Lihat Detail
                    <svg class="h-3 w-3 transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/></svg>
                </span>
            </div>
        </div>
    </div>
</a>
