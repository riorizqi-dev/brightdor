@extends('frontend.layouts.app')

@section('title', 'Masuk — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <p class="bd-section-kicker">Selamat datang kembali</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Masuk ke BrightDor</h1>
                <p class="mt-3 text-sm text-ink-500">Masuk ke akun BrightDor Anda untuk melanjutkan.</p>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-[5px] border border-emerald-500/40 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('frontend.login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="login-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                        <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="bd-input mt-1.5" autofocus>
                        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="login-password" class="text-xs font-bold uppercase tracking-wider text-ink-400">Password</label>
                        <input id="login-password" type="password" name="password" required autocomplete="current-password" class="bd-input mt-1.5">
                        @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-ink-500">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-ink-300 text-rose-600 focus:ring-rose-500">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                        Masuk
                    </button>
                </form>
            </section>

            <p class="mt-6 text-center text-sm text-ink-500">
                Belum punya akun?
                <a href="{{ route('frontend.register.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Daftar sebagai User</a>
            </p>
        </div>
    </div>
@endsection
