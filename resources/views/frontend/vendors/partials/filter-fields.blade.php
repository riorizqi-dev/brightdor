<div class="space-y-5">
    @if (! $category ?? false)
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kategori</h3>
            <div class="mt-2.5 space-y-2">
                @foreach ($categories ?? [] as $cat)
                    <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                        <input type="checkbox" name="category" value="{{ $cat->id }}" @checked(request('category') == $cat->id)
                               class="h-4 w-4 rounded border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                        <span class="flex-1">{{ $cat->name }}</span>
                        <span class="text-xs text-ink-400">{{ $cat->vendors_count }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kota</h3>
        <div class="mt-2.5 space-y-2">
            @foreach ($cities ?? [] as $city => $count)
                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                    <input type="checkbox" name="city" value="{{ $city }}" @checked(request('city') == $city)
                           class="h-4 w-4 rounded border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                    <span class="flex-1">{{ $city }}</span>
                    <span class="text-xs text-ink-400">{{ $count }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Range Harga</h3>
        <div class="mt-2.5 space-y-2">
            @foreach ([1, 2, 3, 4, 5] as $key)
                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                    <input type="radio" name="price" value="{{ $key }}" @checked(request('price') == $key)
                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                    <span>{{ price_range_label((string) $key) }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Kapasitas Tamu</h3>
        <div class="mt-2.5 space-y-2">
            @foreach ([1, 2, 3, 4, 5] as $key)
                <label class="group flex cursor-pointer items-center gap-2.5 text-sm text-ink-600 transition-colors hover:text-rose-600">
                    <input type="radio" name="capacity" value="{{ $key }}" @checked(request('capacity') == $key)
                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                    <span>{{ capacity_range_label((string) $key) }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-ink-900">Rating</h3>
        <div class="mt-2.5 space-y-2">
            @foreach ([4.5, 4, 3, 2] as $min)
                <label class="group flex cursor-pointer items-center gap-2 text-sm text-ink-600 transition-colors hover:text-rose-600">
                    <input type="radio" name="rating" value="{{ $min }}" @checked((float) request('rating') == $min)
                           class="h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50 focus:ring-offset-0">
                    <span class="inline-flex items-center gap-1">
                        <x-frontend.rating-stars :rating="$min" size="sm"/>
                        <span class="ml-0.5 text-xs text-ink-400">&amp; lebih</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>
