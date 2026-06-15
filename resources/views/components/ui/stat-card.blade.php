@props(['label', 'value', 'icon' => null, 'trend' => null])

<x-ui.card {{ $attributes->merge(['class' => 'relative group overflow-hidden']) }}>
    <div class="flex items-start justify-between">
        <div class="space-y-2">
            <x-ui.section-label>{{ $label }}</x-ui.section-label>
            <div class="flex items-baseline space-x-2">
                <h4 class="font-display text-3xl font-bold tracking-tight text-foreground">{{ $value }}</h4>
                @if($trend)
                    <span class="text-xs font-bold {{ $trend > 0 ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                    </span>
                @endif
            </div>
        </div>

        @if($icon)
            <div
                class="p-3 bg-primary/5 rounded-xl text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                {!! $icon !!}
            </div>
        @endif
    </div>

    <!-- Subtle background pattern -->
    <div
        class="absolute -right-4 -bottom-4 opacity-[0.03] text-primary transform rotate-12 transition-transform group-hover:scale-110">
        @if($icon)
            <div class="w-24 h-24">
                {!! $icon !!}
            </div>
        @endif
    </div>
</x-ui.card>