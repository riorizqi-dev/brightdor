@extends('frontend.layouts.app')

@section('title', 'Masuk — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <p class="bd-section-kicker">Selamat datang kembali</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Masuk ke BrightDor</h1>
                <p class="mt-3 text-sm text-ink-500">Masuk sebagai vendor atau admin. Sistem akan mengarahkan Anda ke panel yang sesuai.</p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

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
                        <a href="{{ route('frontend.password.request') }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700 transition-colors">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                        Masuk
                    </button>
                </form>
            </section>

            <p class="mt-6 text-center text-sm text-ink-500">
                Belum punya akun vendor?
                <a href="{{ route('vendors.register.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Mendaftar sebagai Vendor</a>
            </p>
        </div>
    </div>
@endsection
