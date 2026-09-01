@extends('frontend.layouts.app')

@section('title', 'Reset Password — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <p class="bd-section-kicker">Pemulihan Akun</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Atur Password Baru</h1>
                <p class="mt-3 text-sm text-ink-500">Silakan buat password baru untuk akun kamu.</p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('frontend.password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="reset-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                        <input id="reset-email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" class="bd-input mt-1.5">
                        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reset-password" class="text-xs font-bold uppercase tracking-wider text-ink-400">Password Baru</label>
                        <x-frontend.password-field id="reset-password" name="password" autocomplete="new-password" required autofocus />
                        @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reset-password-confirmation" class="text-xs font-bold uppercase tracking-wider text-ink-400">Konfirmasi Password Baru</label>
                        <x-frontend.password-field id="reset-password-confirmation" name="password_confirmation" autocomplete="new-password" required />
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                        Simpan Password Baru
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection
