@extends('frontend.layouts.app')

@section('title', 'Pembayaran Booking — BrightDor')

@php
    $methodLabels = [
        'bank_transfer' => 'Transfer Bank',
        'ewallet' => 'E-Wallet',
        'virtual_account' => 'Virtual Account',
    ];
    $alreadySubmitted = $transaction->payment_method !== null;
@endphp

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-2xl">
            <p class="bd-section-kicker">Pembayaran</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-ink-900">Bayar Booking</h1>
            <p class="mt-2 text-sm text-ink-500">Kode booking: <span class="font-bold text-ink-800">{{ $booking->booking_code }}</span> · {{ $booking->vendor->business_name }}</p>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <dl class="grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Paket</dt>
                        <dd class="mt-1 font-semibold text-ink-800">{{ $booking->service?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Tanggal Acara</dt>
                        <dd class="mt-1 font-semibold text-ink-800">{{ $booking->event_date?->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">Tagihan</dt>
                        <dd class="mt-1 font-display text-2xl font-extrabold text-rose-600">{{ rupiah((float) $transaction->amount) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-ink-400">No. Transaksi</dt>
                        <dd class="mt-1 font-semibold text-ink-800">{{ $transaction->transaction_code }}</dd>
                    </div>
                </dl>
            </section>

            <div class="mt-4 rounded-[5px] border border-ink-200 bg-ink-50 px-4 py-3 text-xs text-ink-500 ring-1 ring-ink-200/60">
                <span class="font-bold text-ink-700">Panduan:</span> transfer ke rekening <span class="font-bold text-ink-700">BCA 1234567890 a.n. PT BrightDor</span>,
                lalu isi metode, nomor referensi transfer, dan unggah bukti (opsional). Tim BrightDor akan memvalidasi pembayaranmu.
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($transaction->status === 'success')
                <div class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    Pembayaran <span class="font-bold">{{ $transaction->transaction_code }}</span> sudah lunas.
                </div>
            @elseif ($alreadySubmitted)
                <div class="mt-6 rounded-md border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-700">
                    Bukti pembayaran sudah terkirim dan sedang menunggu validasi admin. Kamu dapat memperbarui bukti di bawah ini.
                </div>
            @endif

            @if ($transaction->status === 'pending')
                <section class="bd-card mt-6 p-6 sm:p-8">
                    <h2 class="font-display text-lg font-bold text-ink-900">Form Pembayaran Manual</h2>
                    <form method="POST" action="{{ route('my-bookings.payment.store', $booking) }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                        @csrf

                        <div>
                            <label for="pay-method" class="text-xs font-bold uppercase tracking-wider text-ink-400">Metode Pembayaran</label>
                            <select id="pay-method" name="payment_method" required class="bd-input mt-1.5">
                                <option value="">— Pilih metode —</option>
                                @foreach ($methodLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('payment_method', $transaction->payment_method) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="pay-reference" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nomor Referensi / Transaksi</label>
                            <input id="pay-reference" type="text" name="payment_reference" value="{{ old('payment_reference', $transaction->gateway_reference) }}" placeholder="cth. TRX-BANK-BNI-20240701-001" required class="bd-input mt-1.5">
                            @error('payment_reference')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="pay-proof" class="text-xs font-bold uppercase tracking-wider text-ink-400">Bukti Pembayaran (Opsional)</label>
                            <input id="pay-proof" type="file" name="payment_proof" accept="image/png,image/jpeg" class="bd-input mt-1.5 file:border-0 file:bg-ink-100 file:px-4 file:py-2 file:text-ink-600 file:font-bold">
                            <p class="mt-1 text-xs text-ink-400">JPG / PNG, maksimal 2 MB.</p>
                            @error('payment_proof')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit" class="bd-btn-primary flex-1 justify-center py-3">
                                {{ $alreadySubmitted ? 'Perbarui Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                            </button>
                            <a href="{{ route('my-bookings.index') }}" class="bd-btn-secondary flex-1 justify-center py-3">Kembali</a>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>
@endsection