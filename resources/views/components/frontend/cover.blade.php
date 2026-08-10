@props([
    'src' => null,
    'title' => 'Vendor',
    'category' => '',
    'initials' => null,
    'class' => 'aspect-[4/3]',
])

@php
    $initials = $initials ?? collect(preg_split('/\s+/', trim($title)))->filter()
        ->slice(0, 2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $palettes = [
        ['#c6436a', '#a62f55', '#7d1e3d'],
        ['#d9507e', '#b23a60', '#832a49'],
        ['#e8749b', '#c6436a', '#8a2a48'],
        ['#a62f55', '#7d1e3d', '#5c1529'],
        ['#d0b565', '#a8923f', '#6e5c24'],
        ['#8a2a48', '#6e223c', '#4d1629'],
        ['#c6436a', '#8a2a48', '#a8923f'],
    ];
    $palette = $palettes[(crc32($title) % count($palettes))];
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden w-full ' . $class]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $title }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]"/>
    @else
        <div class="absolute inset-0"
             style="background: linear-gradient(135deg, {{ $palette[0] }} 0%, {{ $palette[1] }} 55%, {{ $palette[2] }} 130%);">
            <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full border border-white/10"></div>
            <div class="absolute -bottom-10 -left-6 h-44 w-44 rounded-full border border-white/10"></div>
            <div class="absolute -right-20 -bottom-20 h-60 w-60 rounded-full border border-white/5"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-1 text-center">
                <span class="text-4xl font-display font-bold text-white/90 leading-none drop-shadow-sm">{{ $initials }}</span>
                @if ($category)
                    <span class="mt-2 text-[10px] uppercase tracking-[0.25em] text-white/50 font-medium">{{ $category }}</span>
                @endif
            </div>
        </div>
    @endif
</div>