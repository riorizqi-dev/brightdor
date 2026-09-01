@props(['id' => null, 'name' => 'password', 'autocomplete' => 'current-password', 'autofocus' => false])
{{-- Password input dengan toggle visibility (icon mata) --}}
<div class="bd-password-field relative mt-1.5">
    <input
        @if ($id) id="{{ $id }}" @endif
        type="password"
        name="{{ $name }}"
        autocomplete="{{ $autocomplete }}"
        @if ($autofocus) autofocus @endif
        {{ $attributes->class(['bd-input', 'pr-11']) }}
        data-password-target
    >
    <button
        type="button"
        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-ink-400 transition-colors hover:text-rose-600 focus:outline-none focus-visible:text-rose-600"
        data-password-toggle
        aria-label="Tampilkan password"
        tabindex="-1"
    >
        {{-- Icon mata (password tersembunyi) --}}
        <svg data-password-icon-eye class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.04 12.32C3.42 7.51 7.36 4.5 12 4.5s8.58 3.01 9.96 7.82c.05.18.05.36 0 .54-1.38 4.81-5.32 7.82-9.96 7.82s-8.58-3.01-9.96-7.82a.96.96 0 0 1 0-.54Z"/>
            <circle cx="12" cy="12.4" r="3.1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{-- Icon mata dicoret (password terlihat) --}}
        <svg data-password-icon-eye-off class="hidden h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 5.66C2.6 7.17 1.5 9.03 1 11.25c1.3 5.35 5.6 8.25 11 8.25 2.2 0 4.24-.49 6.04-1.43M12 4.5c5.4 0 9.7 2.9 11 8.25-.46 1.88-1.3 3.5-2.4 4.86M9.88 9.53a3.1 3.1 0 0 0 4.37 4.37M4 4l16 16"/>
        </svg>
    </button>
</div>
