@extends('frontend.layouts.app')

@section('title', 'Pembayaran Booking — BrightDor')

@php
    $methodLabels = [
        'qris' => 'QRIS (Semua E-Wallet & Mobile Banking)',
        'bank_transfer' => 'Transfer Bank Manual',
        'virtual_account' => 'Virtual Account',
        'ewallet' => 'E-Wallet',
    ];
    $alreadySubmitted = $transaction->payment_method !== null;
    $currentMethod = old('payment_method', $transaction->payment_method ?? 'qris');
@endphp

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-3xl">
            {{-- Header --}}
            <nav class="flex items-center gap-1.5 text-xs text-ink-400" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-rose-600 transition-colors">Beranda</a>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('my-bookings.index') }}" class="hover:text-rose-600 transition-colors">Booking Saya</a>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <span class="font-semibold text-ink-700">Pembayaran</span>
            </nav>

            <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="bd-section-kicker">Konfirmasi &amp; Pelunasan</p>
                    <h1 class="mt-1 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Bayar Booking</h1>
                    <p class="mt-1.5 text-sm text-ink-500">
                        Kode booking: <span class="font-bold text-ink-800">{{ $booking->booking_code }}</span> ·
                        Vendor: <span class="font-semibold text-ink-800">{{ $booking->vendor->business_name }}</span>
                    </p>
                </div>
                <div class="rounded-[5px] bg-rose-50 px-4 py-2 text-right ring-1 ring-rose-200/60">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Total Pembayaran</span>
                    <p class="font-display text-2xl font-extrabold text-rose-600">{{ rupiah((float) $transaction->amount) }}</p>
                </div>
            </div>

            {{-- Summary Card --}}
            <section class="bd-card mt-6 p-6">
                <div class="flex items-center justify-between border-b border-ink-100 pb-4">
                    <h2 class="font-display text-base font-bold text-ink-900">Rincian Tagihan</h2>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-300/60">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        {{ $transaction->status === 'success' ? 'Lunas' : ($alreadySubmitted ? 'Menunggu Validasi' : 'Belum Dibayar') }}
                    </span>
                </div>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2 md:grid-cols-4">
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-ink-400">Paket Layanan</dt>
                        <dd class="mt-1 font-semibold text-ink-800">{{ $booking->service?->name ?? 'Paket Layanan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-ink-400">Tanggal Acara</dt>
                        <dd class="mt-1 font-semibold text-ink-800">{{ $booking->event_date?->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-ink-400">No. Transaksi</dt>
                        <dd class="mt-1 font-mono font-bold text-ink-800">{{ $transaction->transaction_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-ink-400">Batas Waktu</dt>
                        <dd class="mt-1 font-semibold text-ink-800">24 Jam</dd>
                    </div>
                </dl>
            </section>

            {{-- Feedback Alerts --}}
            @if (session('success'))
                <div class="mt-6 flex items-start gap-3 rounded-[5px] border border-emerald-500/40 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <div class="flex-1">
                        <p class="font-bold">Berhasil Terkirim</p>
                        <p class="mt-0.5 text-xs text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($transaction->status === 'success')
                <div class="mt-6 flex items-start gap-3 rounded-[5px] border border-emerald-500/40 bg-emerald-50 p-5 text-sm text-emerald-800">
                    <svg class="mt-0.5 h-6 w-6 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <div>
                        <p class="font-bold text-base">Pembayaran Lunas</p>
                        <p class="mt-1 text-xs text-emerald-700">Transaksi <span class="font-mono font-semibold">{{ $transaction->transaction_code }}</span> telah diverifikasi dan status booking Anda telah dikonfirmasi oleh vendor.</p>
                        <a href="{{ route('my-bookings.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-emerald-800 underline hover:text-emerald-900">Kembali ke Daftar Booking &rarr;</a>
                    </div>
                </div>
            @elseif ($alreadySubmitted)
                <div class="mt-6 flex items-start gap-3 rounded-[5px] border border-sky-400/50 bg-sky-50 p-4 text-sm text-sky-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <div>
                        <p class="font-bold">Bukti Pembayaran Sedang Diverifikasi</p>
                        <p class="mt-0.5 text-xs text-sky-700">Tim BrightDor sedang memvalidasi nomor referensi <span class="font-mono font-semibold">{{ $transaction->gateway_reference }}</span>. Jika ada kesalahan data, Anda masih dapat memperbarui bukti di form bawah ini.</p>
                    </div>
                </div>
            @endif

            @if ($transaction->status === 'pending')
                {{-- Payment Method Selection Tabs --}}
                <div class="mt-8">
                    <h2 class="font-display text-xl font-bold text-ink-900">Pilih Jalur Pembayaran</h2>
                    <p class="mt-1 text-xs text-ink-500">Pilih metode yang paling nyaman untuk Anda. Scan QRIS mendukung hampir seluruh perbankan dan dompet digital di Indonesia.</p>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:gap-4">
                        {{-- Tab 1: QRIS --}}
                        <button type="button" id="tab-btn-qris" onclick="switchPaymentTab('qris')"
                                class="payment-tab-btn group relative flex items-center gap-3 rounded-[5px] border p-4 text-left transition-all duration-200 border-rose-500 bg-rose-50/50 shadow-sm ring-1 ring-rose-500/30">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[5px] bg-rose-600 text-white shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.008v.008H6.75V6.75ZM6.75 16.5h.008v.008H6.75V16.5ZM16.5 6.75h.008v.008H16.5V6.75ZM13.5 13.5h3.75m0 0v3.75m0-3.75 3.75 3.75M13.5 19.5h3.75m3.75-3.75v3.75"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-display text-sm font-bold text-ink-900">QRIS Instan</span>
                                    <span class="rounded bg-rose-100 px-1.5 py-0.2 text-[10px] font-extrabold text-rose-700">Rekomendasi</span>
                                </div>
                                <p class="mt-0.5 text-xs text-ink-500">Scan via m-Banking &amp; E-Wallet</p>
                            </div>
                        </button>

                        {{-- Tab 2: Transfer Bank --}}
                        <button type="button" id="tab-btn-bank_transfer" onclick="switchPaymentTab('bank_transfer')"
                                class="payment-tab-btn group relative flex items-center gap-3 rounded-[5px] border p-4 text-left transition-all duration-200 border-ink-200 bg-white hover:border-ink-300">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[5px] bg-ink-100 text-ink-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M2.25 21h19.5"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="font-display text-sm font-bold text-ink-900">Transfer Bank</div>
                                <p class="mt-0.5 text-xs text-ink-500">BCA, Mandiri, BNI, BRI</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- PANEL 1: QRIS INSTAN --}}
                <div id="panel-qris" class="payment-panel mt-6">
                    <div class="bd-card overflow-hidden p-6 sm:p-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="inline-flex items-center gap-2 rounded-full bg-ink-100 px-3 py-1 text-xs font-semibold text-ink-700">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Standar QRIS Nasional — Bebas Biaya Admin
                            </div>

                            {{-- QRIS Mockup Display --}}
                            <div class="mt-6 max-w-xs rounded-xl border border-ink-200 bg-white p-4 shadow-[0_4px_20px_rgba(0,0,0,0.06)]">
                                <img src="{{ asset('images/qris_mockup.png') }}"
                                     alt="QRIS Pembayaran PT BrightDor Indonesia"
                                     class="mx-auto w-full rounded-lg object-contain">
                                <div class="mt-3 text-center border-t border-ink-100 pt-2 text-[11px] text-ink-400">
                                    NMID: <span class="font-mono font-bold text-ink-600">ID1020349812301</span>
                                </div>
                            </div>

                            {{-- Amount to Pay with Copy Button --}}
                            <div class="mt-6 flex flex-wrap items-center justify-center gap-2 rounded-[5px] bg-ink-50 px-4 py-3 ring-1 ring-ink-200/70">
                                <span class="text-xs text-ink-500">Nominal Transfer Tepat:</span>
                                <span class="font-display text-xl font-extrabold text-rose-600">{{ rupiah((float) $transaction->amount) }}</span>
                                <button type="button" onclick="copyText('{{ (int) $transaction->amount }}', this)"
                                        class="inline-flex items-center gap-1 rounded bg-white px-2.5 py-1 text-xs font-bold text-ink-700 shadow-sm ring-1 ring-ink-200 hover:bg-ink-50 transition-colors">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/></svg>
                                    Salin Nominal
                                </button>
                            </div>

                            {{-- Steps --}}
                            <div class="mt-6 w-full max-w-lg rounded-[5px] border border-ink-100 bg-ink-50/60 p-4 text-left">
                                <p class="text-xs font-bold uppercase tracking-wider text-ink-700">Cara Pembayaran QRIS:</p>
                                <ol class="mt-2.5 list-decimal space-y-1.5 pl-4 text-xs text-ink-600 leading-relaxed">
                                    <li>Buka aplikasi mobile banking (BCA Mobile, Livin Mandiri, BRImo, BNI) atau e-wallet (GoPay, OVO, DANA, ShopeePay).</li>
                                    <li>Pilih menu <strong>Scan / Bayar QRIS</strong>.</li>
                                    <li>Arahkan kamera ke kode QRIS di atas atau simpan tangkapan layar untuk discan via galeri.</li>
                                    <li>Pastikan penerima tertulis <strong>PT BRIGHTDOR INDONESIA</strong>.</li>
                                    <li>Periksa kembali nominal tagihan <strong>{{ rupiah((float) $transaction->amount) }}</strong> dan selesaikan transaksi dengan PIN Anda.</li>
                                    <li>Simpan tangkapan layar bukti sukses, catat nomor referensi (RRN), lalu konfirmasikan pada formulir di bawah.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PANEL 2: TRANSFER BANK MANUAL --}}
                <div id="panel-bank_transfer" class="payment-panel mt-6 hidden">
                    <div class="bd-card p-6 sm:p-8">
                        <div class="flex items-center justify-between border-b border-ink-100 pb-4">
                            <div>
                                <h3 class="font-display text-base font-bold text-ink-900">Rekening Resmi PT BrightDor Indonesia</h3>
                                <p class="text-xs text-ink-500">Silakan transfer via ATM, Internet Banking, atau Mobile Banking.</p>
                            </div>
                            <span class="rounded bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200">Rekening Resmi Terverifikasi</span>
                        </div>

                        {{-- Bank Accounts List --}}
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            {{-- BCA --}}
                            <div class="rounded-[5px] border border-ink-200 p-4 bg-white hover:border-ink-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-display font-extrabold text-blue-800 text-lg tracking-wide">BCA</span>
                                    <span class="text-[10px] font-bold text-ink-400 uppercase">Bank Central Asia</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-mono text-base font-bold text-ink-900 tracking-wider">1234 5678 90</p>
                                        <p class="text-[11px] text-ink-500">a.n. PT BrightDor Indonesia</p>
                                    </div>
                                    <button type="button" onclick="copyText('1234567890', this)"
                                            class="rounded bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 ring-1 ring-ink-200 hover:bg-ink-100 transition-colors">
                                        Salin
                                    </button>
                                </div>
                            </div>

                            {{-- Mandiri --}}
                            <div class="rounded-[5px] border border-ink-200 p-4 bg-white hover:border-ink-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-display font-extrabold text-amber-600 text-lg tracking-wide">MANDIRI</span>
                                    <span class="text-[10px] font-bold text-ink-400 uppercase">Bank Mandiri</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-mono text-base font-bold text-ink-900 tracking-wider">137 000 9823 412</p>
                                        <p class="text-[11px] text-ink-500">a.n. PT BrightDor Indonesia</p>
                                    </div>
                                    <button type="button" onclick="copyText('1370009823412', this)"
                                            class="rounded bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 ring-1 ring-ink-200 hover:bg-ink-100 transition-colors">
                                        Salin
                                    </button>
                                </div>
                            </div>

                            {{-- BNI --}}
                            <div class="rounded-[5px] border border-ink-200 p-4 bg-white hover:border-ink-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-display font-extrabold text-teal-700 text-lg tracking-wide">BNI</span>
                                    <span class="text-[10px] font-bold text-ink-400 uppercase">Bank Negara Indonesia</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-mono text-base font-bold text-ink-900 tracking-wider">023 849 1029</p>
                                        <p class="text-[11px] text-ink-500">a.n. PT BrightDor Indonesia</p>
                                    </div>
                                    <button type="button" onclick="copyText('0238491029', this)"
                                            class="rounded bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 ring-1 ring-ink-200 hover:bg-ink-100 transition-colors">
                                        Salin
                                    </button>
                                </div>
                            </div>

                            {{-- BRI --}}
                            <div class="rounded-[5px] border border-ink-200 p-4 bg-white hover:border-ink-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-display font-extrabold text-sky-800 text-lg tracking-wide">BRI</span>
                                    <span class="text-[10px] font-bold text-ink-400 uppercase">Bank Rakyat Indonesia</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-mono text-base font-bold text-ink-900 tracking-wider">0012 0193 8475 801</p>
                                        <p class="text-[11px] text-ink-500">a.n. PT BrightDor Indonesia</p>
                                    </div>
                                    <button type="button" onclick="copyText('001201938475801', this)"
                                            class="rounded bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 ring-1 ring-ink-200 hover:bg-ink-100 transition-colors">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Amount Copy Box --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-[5px] bg-rose-50 p-4 ring-1 ring-rose-200/70">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Nominal yang Harus Ditransfer</span>
                                <p class="font-display text-2xl font-extrabold text-rose-600">{{ rupiah((float) $transaction->amount) }}</p>
                            </div>
                            <button type="button" onclick="copyText('{{ (int) $transaction->amount }}', this)"
                                    class="bd-btn-primary py-2.5 px-5 text-sm font-bold shadow-xs">
                                Salin Jumlah Transfer
                            </button>
                        </div>
                    </div>
                </div>

                {{-- FORM KONFIRMASI PEMBAYARAN --}}
                <section class="bd-card mt-8 p-6 sm:p-8">
                    <div class="border-b border-ink-100 pb-4">
                        <h2 class="font-display text-xl font-bold text-ink-900">Form Konfirmasi Pembayaran</h2>
                        <p class="mt-1 text-xs text-ink-500">Kirimkan bukti atau referensi transfer agar sistem memvalidasi pesanan Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('my-bookings.payment.store', $booking) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="pay-method" class="text-xs font-bold uppercase tracking-wider text-ink-700">Metode Pembayaran</label>
                            <select id="pay-method" name="payment_method" required class="bd-input mt-1.5" onchange="onSelectMethodChange(this.value)">
                                <option value="">— Pilih metode —</option>
                                <option value="qris" @selected($currentMethod === 'qris')>QRIS (Semua E-Wallet &amp; Mobile Banking)</option>
                                <option value="bank_transfer" @selected($currentMethod === 'bank_transfer')>Transfer Bank Manual (BCA, Mandiri, BNI, BRI)</option>
                                <option value="virtual_account" @selected($currentMethod === 'virtual_account')>Virtual Account</option>
                                <option value="ewallet" @selected($currentMethod === 'ewallet')>E-Wallet (GoPay, OVO, DANA)</option>
                            </select>
                            @error('payment_method')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="pay-reference" class="text-xs font-bold uppercase tracking-wider text-ink-700">Nomor Referensi / ID Transaksi Bank</label>
                            <input id="pay-reference" type="text" name="payment_reference"
                                   value="{{ old('payment_reference', $transaction->gateway_reference) }}"
                                   placeholder="Contoh: RRN-20260906-0012 atau TRX-BCA-849201"
                                   required class="bd-input mt-1.5">
                            <p class="mt-1 text-xs text-ink-400">Masukkan nomor referensi transaksi dari struk ATM, riwayat m-Banking, atau mutasi QRIS.</p>
                            @error('payment_reference')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="pay-proof" class="text-xs font-bold uppercase tracking-wider text-ink-700">Unggah Bukti Transfer / Struk (Opsional)</label>
                            <input id="pay-proof" type="file" name="payment_proof" accept="image/png,image/jpeg"
                                   class="bd-input mt-1.5 file:border-0 file:bg-ink-100 file:px-4 file:py-2 file:text-ink-700 file:font-bold file:rounded file:cursor-pointer">
                            <p class="mt-1 text-xs text-ink-400">Format JPG atau PNG, ukuran maksimal 2 MB.</p>
                            @error('payment_proof')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-wrap gap-3 pt-4">
                            <button type="submit" class="bd-btn-primary flex-1 justify-center py-3.5 text-base font-bold shadow-md">
                                {{ $alreadySubmitted ? 'Perbarui Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                            </button>
                            <a href="{{ route('my-bookings.index') }}" class="bd-btn-secondary flex-1 justify-center py-3.5 text-base font-bold">
                                Kembali ke Booking Saya
                            </a>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>

    {{-- Script for interactive tab and copy actions --}}
    <script>
        function switchPaymentTab(method) {
            // Update panels
            const panels = document.querySelectorAll('.payment-panel');
            panels.forEach(p => p.classList.add('hidden'));

            const activePanel = document.getElementById('panel-' + method);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            // Update tab button styles
            const buttons = document.querySelectorAll('.payment-tab-btn');
            buttons.forEach(b => {
                b.classList.remove('border-rose-500', 'bg-rose-50/50', 'shadow-sm', 'ring-1', 'ring-rose-500/30');
                b.classList.add('border-ink-200', 'bg-white');
            });

            const activeBtn = document.getElementById('tab-btn-' + method);
            if (activeBtn) {
                activeBtn.classList.add('border-rose-500', 'bg-rose-50/50', 'shadow-sm', 'ring-1', 'ring-rose-500/30');
                activeBtn.classList.remove('border-ink-200', 'bg-white');
            }

            // Sync with select input
            const selectEl = document.getElementById('pay-method');
            if (selectEl) {
                selectEl.value = method;
            }
        }

        function onSelectMethodChange(value) {
            if (value === 'qris' || value === 'bank_transfer') {
                switchPaymentTab(value);
            }
        }

        function copyText(text, btnElement) {
            navigator.clipboard.writeText(text).then(function() {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerText = 'Tersalin!';
                btnElement.classList.add('bg-emerald-500', 'text-white');
                setTimeout(function() {
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('bg-emerald-500', 'text-white');
                }, 2000);
            }).catch(function() {
                alert('Tersalin: ' + text);
            });
        }

        // Initialize tab based on previous selection or default
        document.addEventListener('DOMContentLoaded', function() {
            const current = '{{ $currentMethod }}';
            if (current === 'bank_transfer') {
                switchPaymentTab('bank_transfer');
            } else {
                switchPaymentTab('qris');
            }
        });
    </script>
@endsection