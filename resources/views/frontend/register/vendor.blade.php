@extends('frontend.layouts.app')

@php($user = auth()->user())

@section('title', 'Daftar sebagai Vendor — BrightDor')

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
                    Daftar sebagai Vendor
                </h1>
                <p class="mt-3 text-sm text-ink-500">
                    Halo, <strong class="text-ink-700">{{ $user->name }}</strong>! Lengkapi data di bawah ini.
                    Menjadi vendor hanya tersedia untuk akun dengan langganan berbayar aktif.
                </p>
            </div>

            <section class="bd-card mt-8 p-6 sm:p-8">
                <form method="POST" action="{{ route('vendors.register.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-ink-400">Paket Vendor</label>
                        <div class="mt-2 space-y-2">
                            <label class="flex cursor-pointer items-start gap-3 rounded-[5px] border border-ink-200 bg-white p-3 text-sm text-ink-700 transition hover:border-rose-300">
                                <input type="radio" name="subscription_plan" value="premium_monthly" checked class="mt-0.5 h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                <span>
                                    <span class="block font-semibold text-ink-900">Premium Monthly</span>
                                    <span class="text-ink-500">Rp 299.000 / bulan • akses vendor penuh</span>
                                </span>
                            </label>
                            <label class="flex cursor-pointer items-start gap-3 rounded-[5px] border border-ink-200 bg-white p-3 text-sm text-ink-700 transition hover:border-rose-300">
                                <input type="radio" name="subscription_plan" value="premium_yearly" class="mt-0.5 h-4 w-4 border-ink-300 text-rose-600 focus:ring-rose-500/50">
                                <span>
                                    <span class="block font-semibold text-ink-900">Premium Yearly</span>
                                    <span class="text-ink-500">Rp 2.990.000 / tahun • hemat 17%</span>
                                </span>
                            </label>
                        </div>
                        @error('subscription_plan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reg-name" class="text-xs font-bold uppercase tracking-wider text-ink-400">Nama Lengkap</label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="bd-input mt-1.5">
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="reg-phone" class="text-xs font-bold uppercase tracking-wider text-ink-400">No. WhatsApp / Telepon</label>
                        <input id="reg-phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required autocomplete="tel" placeholder="cth. 0812xxxxxxx" class="bd-input mt-1.5">
                        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="bd-btn-primary w-full justify-center py-3">Daftar sebagai Vendor</button>
                </form>
            </section>

            <p class="mt-6 text-center text-xs text-ink-400">Ingin keluar dari akun ini? <a href="{{ route('frontend.login.create') }}" class="font-bold text-rose-600 hover:text-rose-700 transition-colors">Keluar</a></p>
        </div>
    </div>
@endsection
