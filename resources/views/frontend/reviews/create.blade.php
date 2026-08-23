@extends('frontend.layouts.app')

@section('title', 'Beri Review — BrightDor')

@php
    $ratingLabels = [
        1 => 'Sangat Buruk',
        2 => 'Buruk',
        3 => 'Biasa',
        4 => 'Baik',
        5 => 'Sangat Baik',
    ];
@endphp

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-2xl">
            <div class="text-center">
                <p class="bd-section-kicker">Review</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Beri Review untuk {{ $booking->vendor?->business_name ?? 'Vendor' }}</h1>
                <p class="mt-3 text-sm text-ink-500">
                    Paket: {{ $booking->service?->name ?? '-' }} · Tanggal: {{ $booking->event_date?->translatedFormat('d M Y') }}
                </p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('my-bookings.review.store', $booking) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="bd-section-kicker block mb-4">Rating</label>
                        <div class="flex items-center gap-3">
                            @foreach ([1, 2, 3, 4, 5] as $value)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $value }}" class="peer sr-only" {{ old('rating') == $value ? 'checked' : '' }}>
                                    <span class="bd-chip peer-checked:border-transparent peer-checked:text-white peer-checked:inv-bg-primary peer-checked:border-rose-600">
                                        {{ $value }} <span class="text-xs opacity-70">({{ $ratingLabels[$value] }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('rating')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="content" class="text-xs font-bold uppercase tracking-wider text-ink-400">Ulasan Anda (Opsional)</label>
                        <textarea id="content" name="content" rows="5" maxlength="2000" class="bd-input mt-1.5" placeholder="Bagikan pengalaman Anda...">{{ old('content') }}</textarea>
                        @error('content')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="bd-btn-primary flex-1 justify-center py-3">Kirim Review</button>
                        <a href="{{ route('my-bookings.index') }}" class="bd-btn-secondary flex-1 justify-center py-3">Batal</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection