@props(['name' => '', 'slug' => null, 'class' => 'h-4 w-4'])

@php
    $key = $slug ?: strtolower($name);
    $paths = [
        'venue' => [
            'M4 21h16M6.5 21V10a5.5 5.5 0 0 1 11 0v11M9.75 21v-4.25h4.5V21',
            'stroke',
        ],
        'catering' => [
            'M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7',
            'stroke',
        ],
        'dekorasi' => [
            'M12 12a1.8 1.8 0 1 0 0 3.6 1.8 1.8 0 0 0 0-3.6ZM12 3.6a2.6 2.6 0 1 0 0 5.2 2.6 2.6 0 0 0 0-5.2ZM12 15.2a2.6 2.6 0 1 0 0 5.2 2.6 2.6 0 0 0 0-5.2ZM3.6 12a2.6 2.6 0 1 0 5.2 0 2.6 2.6 0 0 0-5.2 0ZM15.2 12a2.6 2.6 0 1 0 5.2 0 2.6 2.6 0 0 0-5.2 0Z',
            'stroke',
        ],
        'fotografer' => [
            'M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2v11ZM12 17a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
            'stroke',
        ],
        'videografer' => [
            'M23 7 16 12l7 5V7ZM1 5h15v14H1V5Z',
            'stroke',
        ],
        'mua' => [
            'M12 21a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15ZM12 9.6 13.1 11.5 15 12.6 13.1 13.7 12 15.6 10.9 13.7 9 12.6 10.9 11.5 12 9.6Z',
            'stroke',
        ],
        'wedding-organizer' => [
            'M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2M15 2H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2Zm-6 12 2 2 4-4',
            'stroke',
        ],
        'entertainment' => [
            'M9 18V5l12-2v13M6 21a3 3 0 1 1 0-6 3 3 0 0 1 0 6ZM18 19a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z',
            'stroke',
        ],
        'gaun-jas' => [
            'M20.91 8.84 20.5 7.5A2 2 0 0 0 18.52 6h-3.09l-.25-1.69a2 2 0 0 0-1.98-1.69h-2.4a2 2 0 0 0-1.98 1.69L8.57 6H5.48a2 2 0 0 0-1.98 1.5l-.41 1.34a1 1 0 0 0 .54 1.23l4.65 2.01a2 2 0 0 0 1.12.08.63.63 0 0 1 .77.57 2 2 0 0 0 2 1.77h.58a2 2 0 0 0 2-1.77.63.63 0 0 1 .77-.57 2 2 0 0 0 1.12-.08l4.65-2.01a1 1 0 0 0 .54-1.23ZM20.5 13.5 19 21l-7-4-7 4-1.5-7.5',
            'stroke',
        ],
        'undangan-digital' => [
            'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Zm18 2-10 7L2 6M18 3.5l.6 1.8 1.8.6-1.8.6-.6 1.8-.6-1.8-1.8-.6 1.8-.6.6-1.8Z',
            'stroke',
        ],
    ];
    $icon = $paths[$key] ?? $paths[array_key_first($paths)];
    $path = $icon[0];
    $mode = $icon[1];
@endphp

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $path }}"/>
</svg>
