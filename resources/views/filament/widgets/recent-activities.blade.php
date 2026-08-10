<x-filament-widgets::widget>
    <div class="bd-panel">
        <div class="bd-panel-head">
            <h2>{{ __('brightdor.dashboard.recent_activity') }}</h2>
            <span>{{ __('brightdor.dashboard.recent_activity_hint') }}</span>
        </div>
        <div class="bd-panel-body">
            @php($items = $this->getItems())
            @if ($items->isEmpty())
                <div class="bd-empty">
                    <strong>{{ __('brightdor.dashboard.empty_title') }}</strong>
                    {{ __('brightdor.dashboard.empty_body') }}
                </div>
            @else
                <div class="bd-list">
                    @foreach ($items as $item)
                        <div class="bd-list-item">
                            <div class="bd-list-main">
                                <strong>{{ $item['title'] }}</strong>
                                <span>{{ $item['meta'] }}</span>
                            </div>
                            <span @class([
                                'bd-pill',
                                'bd-pill-warn' => $item['tone'] === 'warn',
                                'bd-pill-ok' => $item['tone'] === 'ok',
                            ])>
                                {{ $item['badge'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
