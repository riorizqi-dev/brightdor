@extends('frontend.layouts.app')

@php
    $upgrade = auth()->check();
    $user = auth()->user();
@endphp

@section('title', $upgrade ? 'Jadikan Akun sebagai Vendor — BrightDor' : 'Daftar sebagai Vendor — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-[5px] border border-emerald-500/40 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <p class="flex-1 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="text-center">
                <p class="bd-section-kicker">Bergabung dengan 3000+ vendor terpercaya</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">
                    {{ $upgrade ? 'Jadikan Akun sebagai Vendor' : 'Daftar sebagai Vendor' }}
                </h1>
                <p class="mt-3 text-sm text-ink-500">
                    @if ($upgrade)
                        Halo, <strong class="text-ink-700">{{ $user->name }}</strong>! Akun Anda sudah terdaftar.
                        Cukup konfirmasi di bawah ini untuk membuka panel vendor.
                    @else
                        Lengkapi data di bawah ini. Tim BrightDor akan menghubungi Anda untuk verifikasi sebelum profil tampil di marketplace.
                    @endif
                </p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('vendors.register.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="reg-name" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nama Lengkap</label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required autocomplete="name" class="bd-input mt-1.5" @if($upgrade) readonly @endif>
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    @if (! $upgrade)
                        <div>
                            <label for="reg-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                            <input id="reg-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="bd-input mt-1.5">
                            @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    <div>
                        <label for="reg-phone" class="text-xs font-bold uppercase tracking-wider text-ink-400">No. WhatsApp / Telepon</label>
                        <input id="reg-phone" type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required autocomplete="tel" placeholder="cth. 0812xxxxxxx" class="bd-input mt-1.5">
                        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    @if (! $upgrade)
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="reg-password" class="text-xs font-bold uppercase tracking-wider text-ink-400">Password</label>
                                <input id="reg-password" type="password" name="password" required autocomplete="new-password" class="bd-input mt-1.5">
                                @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="reg-password-confirm" class="text-xs font-bold uppercase tracking-wider text-ink-400">Konfirmasi Password</label>
                                <input id="reg-password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="bd-input mt-1.5">
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                        {{ $upgrade ? 'Jadikan Saya Vendor' : 'Daftar Sekarang' }}
                    </button>
                </form>
            </section>

            @if ($upgrade)
                <p class="mt-6 text-center text-xs text-ink-400">Ingin keluar dari akun ini? <a href="{{ route('frontend.login.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Keluar</a></p>
            @else
                <p class="mt-6 text-center text-xs text-ink-400">Sudah memiliki akun? <a href="{{ route('frontend.login.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Masuk di sini</a></p>
            @endif
        </div>
    </div>
@endsection
