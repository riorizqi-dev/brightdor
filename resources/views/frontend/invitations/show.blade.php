<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $order = $invitation->order;
        $bride = data_get($content, 'couple.bride.name') ?? $order?->bride_name ?? 'Mempelai Wanita';
        $groom = data_get($content, 'couple.groom.name') ?? $order?->groom_name ?? 'Mempelai Pria';
        $weddingDate = $order?->wedding_date;
        $venue = $order?->wedding_venue;
        $pageTitle = "Undangan Pernikahan {$groom} & {$bride}";
        $primary = data_get($theme, 'primary_color', '#c6436a');
    @endphp

    <title>{{ $pageTitle }}</title>

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="Kami mengundang Anda untuk hadir dan memberikan doa restu pada acara pernikahan kami.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if (data_get($content, 'hero.image'))
        <meta property="og:image" content="{{ data_get($content, 'hero.image') }}">
    @endif
    <meta name="description" content="Undangan pernikahan digital {{ $groom }} & {{ $bride }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --inv-primary: {{ $primary }}; }
        .inv-primary { color: var(--inv-primary); }
        .inv-bg-primary { background-color: var(--inv-primary); }
        .inv-border-primary { border-color: var(--inv-primary); }
    </style>
</head>
<body class="bg-rose-50/40 font-sans text-ink-600 antialiased">

    {{-- ===================== HERO ===================== --}}
    <section class="relative flex min-h-screen items-center justify-center overflow-hidden">
        @if (data_get($content, 'hero.image'))
            <img src="{{ data_get($content, 'hero.image') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-ink-900/50"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-b from-rose-100 via-rose-50 to-white"></div>
            <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full border border-rose-300/40"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-24 h-96 w-96 rounded-full border border-rose-300/30"></div>
        @endif

        <div class="relative z-10 px-6 py-20 text-center {{ data_get($content, 'hero.image') ? 'text-white' : 'text-ink-900' }}">
            <p class="text-xs font-bold uppercase tracking-[0.3em] {{ data_get($content, 'hero.image') ? 'text-white/80' : 'inv-primary' }}">
                {{ data_get($content, 'hero.kicker', 'The Wedding Of') }}
            </p>
            <h1 class="mt-6 font-display text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
                {{ $groom }} <span class="{{ data_get($content, 'hero.image') ? 'text-rose-200' : 'inv-primary' }}">&amp;</span> {{ $bride }}
            </h1>
            @if ($weddingDate)
                <p class="mt-6 text-lg font-medium {{ data_get($content, 'hero.image') ? 'text-white/90' : 'text-ink-500' }}">
                    {{ $weddingDate->translatedFormat('l, d F Y') }}
                </p>
            @endif
            <a href="#rsvp" class="mt-10 inline-flex items-center gap-2 rounded-full px-8 py-3.5 text-sm font-bold text-white shadow-lg transition-transform duration-300 hover:-translate-y-0.5 inv-bg-primary">
                Konfirmasi Kehadiran
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== COUPLE ===================== --}}
    <section class="bd-section">
        <div class="bd-container max-w-4xl text-center">
            <p class="bd-section-kicker">Mempelai</p>
            <h2 class="bd-section-title mt-2">Dua Hati Satu Tujuan</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-ink-500">
                Dengan memohon rahmat dan ridha Tuhan Yang Maha Esa, kami bermaksud menyelenggarakan pernikahan kami:
            </p>

            <div class="mt-12 grid gap-10 sm:grid-cols-2">
                {{-- Groom --}}
                <div class="bd-card p-8">
                    @if (data_get($content, 'couple.groom.photo'))
                        <img src="{{ data_get($content, 'couple.groom.photo') }}" alt="{{ $groom }}" class="mx-auto h-36 w-36 rounded-full object-cover ring-4 ring-rose-100">
                    @else
                        <div class="mx-auto flex h-36 w-36 items-center justify-center rounded-full bg-rose-100 font-display text-4xl font-extrabold inv-primary ring-4 ring-rose-200">
                            {{ \Illuminate\Support\Str::substr($groom, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="mt-5 font-display text-2xl font-bold text-ink-900">{{ $groom }}</h3>
                    @if (data_get($content, 'couple.groom.parents'))
                        <p class="mt-2 text-sm text-ink-500">Putra dari {{ data_get($content, 'couple.groom.parents') }}</p>
                    @endif
                    @if (data_get($content, 'couple.groom.instagram'))
                        <p class="mt-1 text-xs inv-primary">@{{ data_get($content, 'couple.groom.instagram') }}</p>
                    @endif
                </div>

                {{-- Bride --}}
                <div class="bd-card p-8">
                    @if (data_get($content, 'couple.bride.photo'))
                        <img src="{{ data_get($content, 'couple.bride.photo') }}" alt="{{ $bride }}" class="mx-auto h-36 w-36 rounded-full object-cover ring-4 ring-rose-100">
                    @else
                        <div class="mx-auto flex h-36 w-36 items-center justify-center rounded-full bg-rose-100 font-display text-4xl font-extrabold inv-primary ring-4 ring-rose-200">
                            {{ \Illuminate\Support\Str::substr($bride, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="mt-5 font-display text-2xl font-bold text-ink-900">{{ $bride }}</h3>
                    @if (data_get($content, 'couple.bride.parents'))
                        <p class="mt-2 text-sm text-ink-500">Putri dari {{ data_get($content, 'couple.bride.parents') }}</p>
                    @endif
                    @if (data_get($content, 'couple.bride.instagram'))
                        <p class="mt-1 text-xs inv-primary">@{{ data_get($content, 'couple.bride.instagram') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STORY ===================== --}}
    @if (data_get($content, 'story'))
        <section class="bd-section bg-white">
            <div class="bd-container max-w-3xl text-center">
                <p class="bd-section-kicker">Kisah Kami</p>
                <h2 class="bd-section-title mt-2">Perjalanan Cinta</h2>
                <p class="mt-6 whitespace-pre-line text-sm leading-relaxed text-ink-500 sm:text-base">{{ data_get($content, 'story') }}</p>
            </div>
        </section>
    @endif

    {{-- ===================== EVENTS ===================== --}}
    @php
        $events = data_get($content, 'events');
        if (blank($events) && ($weddingDate || $venue)) {
            $events = [[
                'title' => 'Resepsi Pernikahan',
                'date' => $weddingDate?->translatedFormat('l, d F Y'),
                'time' => null,
                'venue' => $venue,
                'address' => null,
                'map_url' => null,
            ]];
        }
    @endphp

    @if (! empty($events))
        <section class="bd-section">
            <div class="bd-container max-w-4xl">
                <div class="text-center">
                    <p class="bd-section-kicker">Acara</p>
                    <h2 class="bd-section-title mt-2">Waktu &amp; Tempat</h2>
                </div>

                <div class="mt-10 grid gap-6 {{ count($events) > 1 ? 'sm:grid-cols-2' : 'sm:grid-cols-1 max-w-xl mx-auto' }}">
                    @foreach ($events as $event)
                        <div class="bd-card p-8 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 inv-primary ring-1 ring-rose-200">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            </div>
                            <h3 class="mt-4 font-display text-xl font-bold text-ink-900">{{ data_get($event, 'title', 'Acara') }}</h3>
                            <div class="mt-4 space-y-1.5 text-sm text-ink-500">
                                @if (data_get($event, 'date'))
                                    <p class="font-semibold text-ink-700">{{ data_get($event, 'date') }}</p>
                                @endif
                                @if (data_get($event, 'time'))
                                    <p>Pukul {{ data_get($event, 'time') }} WIB</p>
                                @endif
                                @if (data_get($event, 'venue'))
                                    <p class="pt-2 font-semibold text-ink-700">{{ data_get($event, 'venue') }}</p>
                                @endif
                                @if (data_get($event, 'address'))
                                    <p>{{ data_get($event, 'address') }}</p>
                                @endif
                            </div>
                            @if (data_get($event, 'map_url'))
                                <a href="{{ data_get($event, 'map_url') }}" target="_blank" rel="noopener" class="bd-btn-secondary mt-6 inline-flex text-xs">
                                    Lihat Peta
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== GALLERY ===================== --}}
    @if (! empty(data_get($content, 'gallery')))
        <section class="bd-section bg-white">
            <div class="bd-container max-w-5xl">
                <div class="text-center">
                    <p class="bd-section-kicker">Galeri</p>
                    <h2 class="bd-section-title mt-2">Momen Bahagia</h2>
                </div>
                <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach (data_get($content, 'gallery') as $photo)
                        <img src="{{ $photo }}" alt="Galeri" class="aspect-square w-full rounded-md object-cover shadow-sm transition-transform duration-300 hover:scale-[1.02]" loading="lazy">
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== RSVP ===================== --}}
    <section id="rsvp" class="bd-section">
        <div class="bd-container max-w-3xl">
            <div class="text-center">
                <p class="bd-section-kicker">RSVP</p>
                <h2 class="bd-section-title mt-2">Konfirmasi Kehadiran</h2>
                <p class="mt-3 text-sm text-ink-500">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.</p>
            </div>

            {{-- RSVP stats --}}
            <div class="mt-8 grid grid-cols-3 gap-4">
                <div class="bd-card p-5 text-center">
                    <p class="font-display text-3xl font-extrabold inv-primary">{{ $invitation->rsvp_yes }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-ink-400">Hadir</p>
                </div>
                <div class="bd-card p-5 text-center">
                    <p class="font-display text-3xl font-extrabold text-amber-500">{{ $invitation->rsvp_maybe }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-ink-400">Ragu</p>
                </div>
                <div class="bd-card p-5 text-center">
                    <p class="font-display text-3xl font-extrabold text-ink-400">{{ $invitation->rsvp_no }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-ink-400">Berhalangan</p>
                </div>
            </div>

            {{-- RSVP form --}}
            <div class="bd-card mt-8 p-6 sm:p-8">
                @if (session('rsvp_success'))
                    <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('rsvp_success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('invitations.rsvp', $invitation->slug) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="guest_name" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nama Lengkap</label>
                        <input id="guest_name" type="text" name="guest_name" value="{{ old('guest_name') }}" required maxlength="255" class="bd-input mt-1.5" placeholder="Nama kamu">
                        @error('guest_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-ink-400">Konfirmasi Kehadiran</span>
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            @foreach (['yes' => 'Hadir', 'maybe' => 'Ragu', 'no' => 'Tidak'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="attendance" value="{{ $value }}" class="peer sr-only" {{ old('attendance', 'yes') === $value ? 'checked' : '' }}>
                                    <span class="block rounded-md border border-ink-200 px-3 py-2.5 text-center text-sm font-semibold text-ink-500 transition-all peer-checked:border-transparent peer-checked:text-white peer-checked:inv-bg-primary">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('attendance')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="guest_count" class="text-xs font-bold uppercase tracking-wider text-ink-400">Jumlah Tamu</label>
                        <select id="guest_count" name="guest_count" class="bd-input mt-1.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ (int) old('guest_count', 1) === $i ? 'selected' : '' }}>{{ $i }} orang</option>
                            @endfor
                        </select>
                        @error('guest_count')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="message" class="text-xs font-bold uppercase tracking-wider text-ink-400">Ucapan &amp; Doa</label>
                        <textarea id="message" name="message" rows="3" maxlength="1000" class="bd-input mt-1.5" placeholder="Tuliskan ucapan & doa terbaik...">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">Kirim Konfirmasi</button>
                </form>
            </div>

            {{-- Wishes --}}
            @if ($rsvps->isNotEmpty())
                <div class="mt-10">
                    <h3 class="text-center font-display text-lg font-bold text-ink-900">Ucapan &amp; Doa ({{ $rsvps->count() }})</h3>
                    <div class="mt-5 max-h-96 space-y-3 overflow-y-auto pr-1">
                        @foreach ($rsvps as $rsvp)
                            <div class="bd-card p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-bold text-ink-900">{{ $rsvp->guest_name }}</p>
                                    <span class="bd-badge {{ $rsvp->attendance === 'yes' ? 'bg-emerald-100 text-emerald-700' : ($rsvp->attendance === 'maybe' ? 'bg-amber-100 text-amber-700' : 'bg-ink-100 text-ink-500') }}">
                                        {{ $rsvp->attendance === 'yes' ? 'Hadir' : ($rsvp->attendance === 'maybe' ? 'Ragu' : 'Tidak Hadir') }}
                                    </span>
                                </div>
                                @if ($rsvp->message)
                                    <p class="mt-2 text-sm leading-relaxed text-ink-500">{{ $rsvp->message }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-t border-rose-100 bg-white py-10 text-center">
        <p class="font-display text-xl font-extrabold text-ink-900">{{ $groom }} &amp; {{ $bride }}</p>
        <p class="mt-2 text-xs text-ink-400">Undangan digital dibuat dengan <a href="{{ url('/') }}" class="bd-link">BrightDor</a></p>
    </footer>
</body>
</html>
