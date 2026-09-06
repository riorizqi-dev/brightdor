@props([
    'src' => null,
    'title' => 'Vendor',
    'category' => '',
    'initials' => null,
    'class' => 'aspect-[4/3]',
])

@php
    $catSlug = strtolower(trim((string) $category));
    $fallbackImage = match(true) {
        str_contains($catSlug, 'venue') => '/images/defaults/venue.jpg',
        str_contains($catSlug, 'catering') => '/images/defaults/catering.jpg',
        str_contains($catSlug, 'dekorasi') => '/images/defaults/dekorasi.jpg',
        str_contains($catSlug, 'fotografer') => '/images/defaults/fotografer.jpg',
        str_contains($catSlug, 'videografer') => '/images/defaults/fotografer.jpg',
        str_contains($catSlug, 'mua') || str_contains($catSlug, 'makeup') => '/images/defaults/mua.jpg',
        str_contains($catSlug, 'wedding organizer') || str_contains($catSlug, 'organizer') => '/images/defaults/wedding_organizer.jpg',
        str_contains($catSlug, 'entertainment') || str_contains($catSlug, 'musik') => '/images/defaults/entertainment.jpg',
        str_contains($catSlug, 'gaun') || str_contains($catSlug, 'jas') => '/images/defaults/gaun_jas.jpg',
        str_contains($catSlug, 'undangan') => '/images/defaults/undangan_digital.jpg',
        default => '/images/defaults/general.jpg',
    };

    // Normalize absolute URL to root-relative so it always matches browser host and port
    if ($src && preg_match('#^https?://[^/]+(/storage/.*)$#', $src, $m)) {
        $src = $m[1];
    }

    $activeSrc = !empty($src) ? $src : $fallbackImage;
    $fallbackAsset = asset(ltrim($fallbackImage, '/'));
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden w-full ' . $class]) }}>
    <img src="{{ $activeSrc }}"
         alt="{{ $title }}"
         loading="lazy"
         decoding="async"
         onerror="if (this.src !== '{{ $fallbackAsset }}') { this.src='{{ $fallbackAsset }}'; }"
         class="absolute inset-0 h-full w-full object-cover transition duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]"/>
</div>