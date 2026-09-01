{{--
    Panel logo. Mirrors the customer-facing navigation lockup (resources/views/components/frontend/navigation.blade.php).

    Uses the .bd-brand classes defined in resources/css/filament/admin/theme.css so the
    mark auto-adapts: white wordmark + gold accent on the maroon topbar, and dark/rose
    on the white login card (.fi-simple-page overrides). Structure stays identical to
    the customer site (ring icon disc + "BrightDor" wordmark + "Premier Wedding" kicker).
--}}
<div class="bd-brand shrink-0">
    <span class="bd-brand-mark">
        <x-frontend.ring-icon class="h-5 w-5" />
    </span>
    <span class="bd-brand-text">
        <span class="bd-brand-word">Bright<span class="bd-brand-word-accent">Dor</span></span>
        <span class="bd-brand-kicker">Premier Wedding</span>
    </span>
</div>
