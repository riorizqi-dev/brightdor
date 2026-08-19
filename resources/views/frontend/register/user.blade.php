@extends('frontend.layouts.app')

@section('title', 'Daftar Akun — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <p class="bd-section-kicker">Mulai perjalanan Anda</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Buat Akun BrightDor</h1>
                <p class="mt-3 text-sm text-ink-500">Daftar sebagai user terlebih dahulu untuk menjelajahi vendor dan layanan pernikahan.</p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('frontend.register.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="reg-name" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nama Lengkap</label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="bd-input mt-1.5">
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reg-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                        <input id="reg-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="bd-input mt-1.5">
                        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reg-phone" class="text-xs font-bold uppercase tracking-wider text-ink-400">No. WhatsApp / Telepon</label>
                        <input id="reg-phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="bd-input mt-1.5">
                        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

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

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">Daftar sebagai User</button>
                </form>
            </section>

            <p class="mt-6 text-center text-sm text-ink-500">Sudah memiliki akun? <a href="{{ route('frontend.login.create') }}" class="font-bold text-rose-600 hover:text-rose-700">Masuk di sini</a></p>
        </div>
    </div>
@endsection