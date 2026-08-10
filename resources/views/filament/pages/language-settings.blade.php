<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bd-panel">
            <div class="bd-panel-head">
                <h2>{{ __('brightdor.language.current') }}</h2>
                <span>{{ strtoupper($this->getCurrentLocale()) }}</span>
            </div>
            <div class="bd-panel-body">
                <p class="mb-5 text-sm text-[#6b7280]">
                    {{ __('brightdor.language.hint') }}
                </p>

                <div class="bd-lang-grid">
                    @foreach ($this->getLocales() as $code => $meta)
                        <button
                            type="button"
                            wire:click="switchLocale('{{ $code }}')"
                            @class([
                                'bd-lang-card',
                                'is-active' => $this->getCurrentLocale() === $code,
                            ])
                        >
                            <div class="code">{{ $code }}</div>
                            <div class="name">{{ $meta['name'] }}</div>
                            <div class="native">{{ $meta['native'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
