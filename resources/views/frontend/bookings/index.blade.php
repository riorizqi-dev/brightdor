@extends('frontend.layouts.app')

@section('title', 'Booking Saya — BrightDor')

@php
    $statusStyles = [
        'pending' => 'bg-amber-100 text-amber-700',
        'confirmed' => 'bg-sky-100 text-sky-700',
        'on_progress' => 'bg-indigo-100 text-indigo-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
        'cancelled' => 'bg-ink-100 text-ink-500',
        'refund' => 'bg-rose-100 text-rose-700',
    ];
    $statusLabels = [
        'pending' => 'Menunggu',
        'confirmed' => 'Terkonfirmasi',
        'on_progress' => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'refund' => 'Refund',
    ];
@endphp

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-4xl">
            <div class="flex items-end justify-between">
                <div>
                    <h1 class="font-display text-2xl font-semibold tracking-tight text-ink-900 sm:text-3xl">Booking Saya</h1>
                    <p class="mt-1 text-sm text-ink-500">Semua pemesanan kamu di satu tempat.</p>
                </div>
                <a href="{{ route('vendors.index') }}" class="bd-btn-secondary text-sm">Booking Vendor Lain</a>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @error('booking')
                <div class="mt-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                    {{ $message }}
                </div>
            @enderror

            @if ($bookings->isEmpty())
                <div class="bd-card mt-8 p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <h2 class="mt-4 font-display text-xl font-bold text-ink-900">Belum ada booking</h2>
                    <p class="mt-2 text-sm text-ink-500">Mulai jelajahi vendor pernikahan dan buat booking pertamamu.</p>
                    <a href="{{ route('vendors.index') }}" class="bd-btn-primary mt-6 inline-flex">Jelajahi Vendor</a>
                </div>
            @else
                <div class="mt-8 space-y-4">
                    @foreach ($bookings as $booking)
                        @php
                            $paymentTxn = $booking->transactions->first(static fn ($t) => (string) $t->type === 'payment');
                        @endphp
                        <article class="bd-card p-6">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h2 class="font-display text-lg font-bold text-ink-900">{{ $booking->vendor->business_name }}</h2>
                                        <span class="bd-badge {{ $statusStyles[$booking->status] ?? 'bg-ink-100 text-ink-500' }}">
                                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-ink-400">
                                        {{ $booking->booking_code }} · {{ $booking->vendor->category->name ?? '-' }}
                                    </p>
                                </div>
                                <p class="font-display text-lg font-extrabold text-rose-600">
                                    Rp{{ number_format((float) $booking->total_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="bd-divider my-4"></div>

                            <dl class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Paket</dt>
                                    <dd class="mt-1 font-semibold text-ink-700">{{ $booking->service->name ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Tanggal Acara</dt>
                                    <dd class="mt-1 font-semibold text-ink-700">
                                        {{ $booking->event_date?->translatedFormat('d M Y') ?? '-' }}{{ $booking->event_time ? ' · ' . \Illuminate\Support\Str::substr((string) $booking->event_time, 0, 5) : '' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Lokasi</dt>
                                    <dd class="mt-1 font-semibold text-ink-700">{{ $booking->event_location ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Jumlah Tamu</dt>
                                    <dd class="mt-1 font-semibold text-ink-700">{{ $booking->guest_count ? number_format($booking->guest_count) . ' tamu' : '-' }}</dd>
                                </div>
                            </dl>

                            @if ($booking->status === 'cancelled' && $booking->cancellation_reason)
                                <p class="mt-4 rounded-md bg-ink-50 px-4 py-2.5 text-xs text-ink-500">
                                    <span class="font-bold">Alasan pembatalan:</span> {{ $booking->cancellation_reason }}
                                </p>
                            @endif

                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a href="{{ route('vendors.show', $booking->vendor->slug) }}" class="bd-btn-ghost px-4 py-2.5 text-sm font-bold ring-1 ring-ink-200">Lihat Vendor</a>

                                @if ($booking->status === 'pending' || ($booking->status === 'confirmed' && $paymentTxn && in_array($paymentTxn->status, ['pending', 'expired', 'failed'], true)))
                                    @if ($paymentTxn && $paymentTxn->status === 'success')
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-100 px-4 py-2.5 text-sm font-bold text-emerald-800">Lunas</span>
                                    @elseif ($paymentTxn && $paymentTxn->status === 'expired')
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-rose-100 px-4 py-2.5 text-sm font-bold text-rose-700">Pembayaran Kedaluwarsa</span>
                                    @elseif ($paymentTxn && $paymentTxn->status === 'failed')
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-rose-100 px-4 py-2.5 text-sm font-bold text-rose-700">Pembayaran Ditolak</span>
                                        <a href="{{ route('my-bookings.payment', $booking) }}" class="bd-btn-secondary px-4 py-2.5 text-sm font-bold">Lihat Detail</a>
                                    @elseif ($paymentTxn && $paymentTxn->status === 'pending' && $paymentTxn->payment_method)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-sky-100 px-4 py-2.5 text-sm font-bold text-sky-800">Menunggu Validasi Admin</span>
                                        <a href="{{ route('my-bookings.payment', $booking) }}" class="bd-btn-secondary px-4 py-2.5 text-sm font-bold">Lihat / Update Bukti</a>
                                    @else
                                        <a href="{{ route('my-bookings.payment', $booking) }}" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-rose-700 hover:shadow-md">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25c0-1.5 1.5-2.25 3-2.25h13.5c1.5 0 3 .75 3 2.25v3m-19.5 0L2.25 22h19.5l-1.5-10.75M15 15.75h3.75"/></svg>
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                @endif

                                @if (in_array($booking->status, ['pending', 'confirmed'], true))
                                    <form method="POST" action="{{ route('my-bookings.cancel', $booking) }}"
                                          onsubmit="return confirm('Yakin ingin membatalkan booking {{ $booking->booking_code }}?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-600 transition-all hover:bg-rose-600 hover:text-white hover:border-rose-600">
                                            Batalkan Booking
                                        </button>
                                    </form>
                                @endif

                                @if ($booking->status === 'completed' && ! $booking->review)
                                    <a href="{{ route('my-bookings.review.create', $booking) }}" class="bd-btn-secondary px-4 py-2.5 text-sm font-bold">Beri Review</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
