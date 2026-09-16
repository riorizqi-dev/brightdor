<x-filament-panels::page>
    @php
        $vendor = $this->getVendor();
        $stats = $this->getStats();
        $recent = $this->getRecentBookings();
    @endphp

    <div class="bd-page">
        {{-- Greeting: slim --}}
        <div class="flex flex-wrap items-end justify-between gap-3 pb-5">
            <div>
                <p class="bd-greeting-kicker">{{ __('brightdor.vendor_dashboard.kicker') }}</p>
                <h1 class="mt-1 font-display text-2xl font-semibold tracking-tight text-[#2a2520] sm:text-[1.7rem]">
                    {{ __('brightdor.vendor_dashboard.welcome', ['name' => auth()->user()->name]) }}
                </h1>
                <p class="mt-1 text-sm text-[#8f837a]">
                    @if ($vendor)
                        {{ __('brightdor.vendor_dashboard.intro_with_vendor', ['business' => $vendor->business_name]) }}
                    @else
                        {{ __('brightdor.vendor_dashboard.intro_no_vendor') }}
                    @endif
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full border border-[#e6e2df] bg-white px-3.5 py-1.5 text-xs font-medium text-[#8f837a]">
                @if ($vendor && $vendor->is_verified)
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    {{ __('brightdor.vendor_dashboard.verified') }}
                @else
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    {{ __('brightdor.vendor_dashboard.pending_review') }}
                @endif
                <span class="text-[#d3ccc7]">·</span>
                {{ now()->translatedFormat('d M Y') }}
            </div>
        </div>

        @if ($vendor)
            {{-- Stats: compact grid --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-5">
                <div class="rounded-xl border border-[#e6e2df] bg-white p-4 transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8f837a]">{{ __('brightdor.vendor_dashboard.bookings') }}</span>
                    <div class="mt-2 font-display text-[1.35rem] font-semibold leading-none tracking-tight text-[#2a2520]">{{ $stats['bookings'] }}</div>
                    <div class="mt-1.5 text-[11px] text-[#8f837a]">
                        @if ($stats['rating_avg'])
                            {{ __('brightdor.vendor_dashboard.rating') }}: <strong class="text-[#574e46]">{{ $stats['rating_avg'] }}</strong> ({{ $stats['rating_count'] }})
                        @else
                            {{ __('brightdor.vendor_dashboard.rating') }}: —
                        @endif
                    </div>
                </div>

                <div class="rounded-xl border border-[#e6e2df] bg-white p-4 transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8f837a]">{{ __('brightdor.common.pending') }}</span>
                    <div class="mt-2 font-display text-[1.35rem] font-semibold leading-none tracking-tight text-[#2a2520]">{{ $stats['pending'] }}</div>
                    <div class="mt-1.5 text-[11px] text-[#8f837a]">{{ __('brightdor.vendor_dashboard.pending_review') }}</div>
                </div>

                <div class="rounded-xl border border-[#e6e2df] bg-white p-4 transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8f837a]">{{ __('brightdor.vendor_dashboard.confirmed') }}</span>
                    <div class="mt-2 font-display text-[1.35rem] font-semibold leading-none tracking-tight text-[#2a2520]">{{ $stats['confirmed'] }}</div>
                    <div class="mt-1.5 text-[11px] text-[#8f837a]">{{ __('brightdor.vendor_dashboard.confirmed') }}</div>
                </div>

                <div class="rounded-xl border border-[#e6e2df] bg-white p-4 transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8f837a]">{{ __('brightdor.vendor_dashboard.completed') }}</span>
                    <div class="mt-2 font-display text-[1.35rem] font-semibold leading-none tracking-tight text-[#2a2520]">{{ $stats['completed'] }}</div>
                    <div class="mt-1.5 text-[11px] text-[#8f837a]">{{ __('brightdor.vendor_dashboard.completed') }}</div>
                </div>

                <div class="rounded-xl border border-[#e6e2df] bg-white p-4 transition-all duration-200 hover:border-rose-300 hover:shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8f837a]">{{ __('brightdor.vendor_dashboard.services') }}</span>
                    <div class="mt-2 font-display text-[1.35rem] font-semibold leading-none tracking-tight text-[#2a2520]">{{ $stats['services'] }}</div>
                    <div class="mt-1.5 text-[11px] text-[#8f837a]">
                        @if ($vendor->is_verified)
                            {{ __('brightdor.vendor_dashboard.verified') }}
                        @else
                            {{ __('brightdor.vendor_dashboard.status_pending') }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payout balance --}}
            <div class="bd-panel">
                <div class="bd-panel-head">
                    <h2>{{ __('brightdor.vendor_dashboard.available_balance') }}</h2>
                    <span
                        @class([
                            'bd-pill',
                            'bd-pill-ok' => $stats['available'] > 0,
                            'bd-pill-warn' => $stats['available'] <= 0,
                        ])
                    >
                        Rp {{ number_format($stats['available'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="bd-panel-body">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="text-[0.85rem] text-[#6b7280]">
                            @if ($stats['available'] > 0)
                                {{ __('brightdor.vendor_dashboard.available_balance_hint') }}
                            @else
                                {{ __('brightdor.vendor_dashboard.no_balance') }}
                            @endif
                        </div>
                        @if ($stats['available'] > 0)
                            <a
                                class="bd-action"
                                href="{{ route('filament.vendor.resources.vendor-payout.vendor-payouts.create') }}"
                            >
                                <span class="bd-action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </span>
                                <span>
                                    <strong>{{ __('brightdor.vendor_dashboard.request_payout') }}</strong>
                                    <small>{{ __('brightdor.vendor_dashboard.request_payout_hint') }}</small>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panels --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                {{-- Recent bookings --}}
                <div class="bd-panel xl:col-span-8">
                    <div class="bd-panel-head">
                        <h2>{{ __('brightdor.vendor_dashboard.recent_bookings') }}</h2>
                        <span>{{ __('brightdor.vendor_dashboard.total_bookings', ['count' => $stats['bookings']]) }}</span>
                    </div>
                    <div class="bd-panel-body">
                        @if ($recent->isNotEmpty())
                            <div class="bd-list">
                                @foreach ($recent as $booking)
                                    <div class="bd-list-item">
                                        <div class="bd-list-main">
                                            <strong>{{ $booking->booking_code }}</strong>
                                            <span>
                                                {{ $booking->service?->name }}
                                                @if ($booking->user)
                                                    · {{ $booking->user->name }}
                                                @endif
                                                @if ($booking->event_date)
                                                    · {{ $booking->event_date->translatedFormat('d M Y') }}
                                                @endif
                                            </span>
                                        </div>
                                        <span
                                            @class([
                                                'bd-pill',
                                                'bd-pill-warn' => $booking->status === 'pending',
                                                'bd-pill-ok' => in_array($booking->status, ['confirmed', 'completed'], true),
                                            ])
                                        >
                                            {{ __("brightdor.vendor_dashboard.{$booking->status}") }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bd-empty">
                                <strong>{{ __('brightdor.vendor_dashboard.no_bookings') }}</strong>
                                <span>{{ __('brightdor.vendor_dashboard.no_bookings_hint') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Vendor profile --}}
                <div class="bd-panel xl:col-span-4">
                    <div class="bd-panel-head">
                        <h2>{{ __('brightdor.vendor_dashboard.vendor_profile') }}</h2>
                        <span
                            @class([
                                'bd-pill',
                                'bd-pill-ok' => $vendor->status === 'approved',
                                'bd-pill-warn' => $vendor->status === 'pending',
                            ])
                        >
                            {{ __("brightdor.vendor_dashboard.status_{$vendor->status}") }}
                        </span>
                    </div>
                    <div class="bd-panel-body">
                        <div class="space-y-2">
                            <strong class="block text-[0.95rem] font-semibold tracking-tight text-[#141414]">
                                {{ $vendor->business_name }}
                            </strong>
                            <div class="space-y-1 text-[0.82rem] text-[#6b7280]">
                                @if ($vendor->category)
                                    <div>{{ __('brightdor.vendor_dashboard.category') }}: {{ $vendor->category->name }}</div>
                                @endif
                                @if ($vendor->city)
                                    <div>{{ __('brightdor.vendor_dashboard.city') }}: {{ $vendor->city }}</div>
                                @endif
                                <div>
                                    {{ __('brightdor.vendor_dashboard.rating') }}:
                                    @if ($stats['rating_avg'])
                                        <strong class="font-semibold text-[#3f3f46]">{{ $stats['rating_avg'] }}</strong> ({{ $stats['rating_count'] }})
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a class="bd-action mt-4" href="{{ route('vendors.show', $vendor->slug) }}">
                            <span class="bd-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            </span>
                            <span>
                                <strong>{{ __('brightdor.vendor_dashboard.view_public_profile') }}</strong>
                                <small>/vendor/{{ $vendor->slug }}</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Onboarding / awaiting admin approval --}}
            <div class="bd-panel">
                <div class="bd-panel-head">
                    <h2>{{ __('brightdor.vendor_dashboard.profile_not_linked') }}</h2>
                </div>
                <div class="bd-panel-body">
                    <div class="bd-empty">
                        <strong>{{ __('brightdor.vendor_dashboard.profile_not_linked') }}</strong>
                        <span>{{ __('brightdor.vendor_dashboard.profile_not_linked_hint') }}</span>
                        <a class="bd-action mt-4" href="{{ route('filament.vendor.resources.vendor-profile.vendor-profiles.create') }}">
                            <span class="bd-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </span>
                            <span>
                                <strong>Lengkapi Profil Vendor</strong>
                                <small>Buat profil agar tampil di marketplace</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
