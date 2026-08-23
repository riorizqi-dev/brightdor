@extends('frontend.layouts.app')

@section('title', 'Lupa Password — BrightDor')

@section('content')
    <div class="bd-container py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <p class="bd-section-kicker">Pemulihan Akun</p>
                <h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-ink-900 sm:text-4xl">Lupa Password</h1>
                <p class="mt-3 text-sm text-ink-500">Masukkan email kamu. Kami akan mengirimkan tautan untuk mengatur ulang password.</p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('frontend.password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="forgot-email" class="text-xs font-bold uppercase tracking-wider text-ink-400">Email</label>
                        <input id="forgot-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="bd-input mt-1.5" autofocus>
                        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">
                        Kirim Tautan Reset
                    </button>
                </form>
            </section>

            <p class="mt-6 text-center text-sm text-ink-500">
                Sudah ingat password?
                <a href="{{ route('frontend.login.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Kembali Masuk</a>
            </p>
        </div>
    </div>
@endsection
