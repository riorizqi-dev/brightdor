@props(['name' => '', 'slug' => null, 'class' => 'h-4 w-4'])

@php
    // Konsisten memakai satu sumber icon: Heroicons v2 (outline) bawaan Filament,
    // supaya tidak menambah dependency baru dan warnanya mengikuti brand (currentColor).
    $key = $slug ?: \Illuminate\Support\Str::slug((string) $name);

    $map = [
        'venue' => 'heroicon-o-building-storefront',
        'catering' => 'heroicon-o-cake',
        'dekorasi' => 'heroicon-o-sparkles',
        'fotografer' => 'heroicon-o-camera',
        'videografer' => 'heroicon-o-video-camera',
        'mua' => 'heroicon-o-paint-brush',
        'wedding-organizer' => 'heroicon-o-clipboard-document-check',
        'entertainment' => 'heroicon-o-musical-note',
        'gaun-jas' => 'heroicon-o-shopping-bag',
        'undangan-digital' => 'heroicon-o-envelope',
    ];

    $icon = $map[$key] ?? 'heroicon-o-tag';
@endphp

@svg($icon, $attributes->merge(['class' => $class])->get('class'), ['aria-hidden' => 'true'])

