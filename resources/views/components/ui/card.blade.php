@props(['variant' => 'default'])

@php
    $base = "rounded-xl overflow-hidden transition-all duration-300";

    $variants = [
        'default' => 'bg-card text-card-foreground shadow-sm border border-border/50',
        'glass' => 'glass-surface text-foreground shadow-glass border-white/20',
        'featured' => 'bg-card text-card-foreground shadow-xl border-l-4 border-primary',
        'muted' => 'bg-muted/50 border border-border/40 text-muted-foreground shadow-none',
        'inverted' => 'bg-foreground text-card shadow-xl',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-border/10">
            <h3 class="font-display text-lg font-semibold tracking-tight">{{ $header }}</h3>
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-muted/30 border-t border-border/10">
            {{ $footer }}
        </div>
    @endif
</div>