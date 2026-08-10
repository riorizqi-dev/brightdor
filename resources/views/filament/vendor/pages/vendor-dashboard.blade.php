<x-filament-panels::page>
    @php
        $vendor = $this->getVendor();
        $stats = $this->getStats();
        $recent = $this->getRecentBookings();
    @endphp

    <div class="bd-page">
        {{-- Greeting --}}
        <div class="bd-greeting">
            <div>
                <div class="bd-greeting-kicker">{{ __('brightdor.vendor_dashboard.kicker') }}</div>
                <h1>{{ __('brightdor.vendor_dashboard.welcome', ['name' => auth()->user()->name]) }}</h1>
                <p>
                    @if ($vendor)
                        {{ __('brightdor.vendor_dashboard.intro_with_vendor', ['business' => $vendor->business_name]) }}
                    @else
                        {{ __('brightdor.vendor_dashboard.intro_no_vendor') }}
                    @endif
                </p>
            </div>
            <div class="bd-greeting-meta">
                <span class="bd-greeting-dot"></span>
                <span>
                    @if ($vendor && $vendor->is_verified)
                        {{ __('brightdor.vendor_dashboard.verified') }}
                    @else
                        {{ __('brightdor.vendor_dashboard.pending_review') }}
                    @endif
                    · {{ now()->translatedFormat('d M Y') }}
                </span>
            </div>
        </div>

        @if ($vendor)
            {{-- Stats --}}
            <div class="bd-stats">
                <div class="bd-stat">
                    <div class="bd-stat-top">
                        <div class="bd-stat-label">{{ __('brightdor.vendor_dashboard.bookings') }}</div>
                        <div class="bd-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        </div>
                    </div>
                    <div class="bd-stat-value">{{ $stats['bookings'] }}</div>
                    <div class="bd-stat-hint">
                        @if ($stats['rating_avg'])
                            {{ __('brightdor.vendor_dashboard.rating') }}: <strong>{{ $stats['rating_avg'] }}</strong> ({{ $stats['rating_count'] }})
                        @else
                            {{ __('brightdor.vendor_dashboard.rating') }}: —
                        @endif
                    </div>
                </div>

                <div class="bd-stat">
                    <div class="bd-stat-top">
                        <div class="bd-stat-label">{{ __('brightdor.common.pending') }}</div>
                        <div class="bd-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                    </div>
                    <div class="bd-stat-value">{{ $stats['pending'] }}</div>
                    <div class="bd-stat-hint">{{ __('brightdor.vendor_dashboard.pending_review') }}</div>
                </div>

                <div class="bd-stat">
                    <div class="bd-stat-top">
                        <div class="bd-stat-label">{{ __('brightdor.vendor_dashboard.confirmed') }}</div>
                        <div class="bd-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                    </div>
                    <div class="bd-stat-value">{{ $stats['confirmed'] }}</div>
                    <div class="bd-stat-hint">{{ __('brightdor.vendor_dashboard.confirmed') }}</div>
                </div>

                <div class="bd-stat">
                    <div class="bd-stat-top">
                        <div class="bd-stat-label">{{ __('brightdor.vendor_dashboard.completed') }}</div>
                        <div class="bd-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                    </div>
                    <div class="bd-stat-value">{{ $stats['completed'] }}</div>
                    <div class="bd-stat-hint">{{ __('brightdor.vendor_dashboard.completed') }}</div>
                </div>

                <div class="bd-stat">
                    <div class="bd-stat-top">
                        <div class="bd-stat-label">{{ __('brightdor.vendor_dashboard.services') }}</div>
                        <div class="bd-stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" /></svg>
                        </div>
                    </div>
                    <div class="bd-stat-value">{{ $stats['services'] }}</div>
                    <div class="bd-stat-hint">
                        @if ($vendor->is_verified)
                            {{ __('brightdor.vendor_dashboard.verified') }}
                        @else
                            {{ __('brightdor.vendor_dashboard.status_pending') }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panels --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {{-- Recent bookings --}}
                <div class="bd-panel xl:col-span-2">
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
                <div class="bd-panel">
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
                                <small>/vendors/vendor/{{ $vendor->slug }}</small>
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
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
