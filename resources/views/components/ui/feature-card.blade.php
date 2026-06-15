@props(['title', 'description', 'icon' => null])

<x-ui.card {{ $attributes->merge(['variant' => 'glass', 'class' => 'group hover:border-primary/30 transition-all duration-500']) }}>
    <div class="flex flex-col h-full">
        @if($icon)
            <div
                class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 transition-all duration-500 group-hover:scale-110 group-hover:bg-primary group-hover:text-white shadow-lg shadow-primary/5">
                {!! $icon !!}
            </div>
        @endif

        <h3
            class="font-display text-xl font-bold text-foreground mb-3 tracking-tight group-hover:text-primary transition-colors">
            {{ $title }}
        </h3>
        <p class="text-sm text-muted-foreground leading-relaxed flex-grow">{{ $description }}</p>

        <div class="mt-6 pt-6 border-t border-border/10">
            <span
                class="inline-flex items-center text-xs font-bold text-primary uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-[-10px] group-hover:translate-x-0">
                Explore Feature
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </span>
        </div>
    </div>
</x-ui.card>