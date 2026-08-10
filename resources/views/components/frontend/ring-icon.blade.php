@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M12 3.5 15 7.5l-3 4-3-4 3-4Z"/>
    <path d="M12 11.5v3"/>
    <circle cx="12" cy="16.5" r="4.5"/>
</svg>
