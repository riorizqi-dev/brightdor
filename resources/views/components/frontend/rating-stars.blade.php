@props([
    'rating' => 0,
    'size' => 'sm',
])

@php
    $sizeMap = [
        'sm' => 'h-3.5 w-3.5',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5',
    ];
    $box = $sizeMap[$size] ?? $sizeMap['sm'];
    $fullStars = (int) round((float) $rating);
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5']) }}>
    @for ($i = 1; $i <= 5; $i++)
        <svg class="{{ $box }} shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="{{ $i <= $fullStars ? '#c6436a' : '#e4e4e4' }}"
                  d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
    @endfor
</div>