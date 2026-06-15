@props(['variant' => 'primary', 'size' => 'md', 'tag' => 'button'])

@php
    $base = "inline-flex items-center justify-center font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed";

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary/90 shadow-md shadow-primary/20 focus:ring-primary',
        'primary-gradient' => 'bg-gradient-to-r from-primary to-accent text-white hover:opacity-90 shadow-lg shadow-primary/30 focus:ring-primary',
        'secondary' => 'bg-white border border-border text-foreground hover:bg-muted focus:ring-primary',
        'secondary-outline' => 'bg-transparent border-2 border-primary text-primary hover:bg-primary/5 focus:ring-primary',
        'accent' => 'bg-accent text-white hover:bg-accent/90 shadow-md shadow-accent/20 focus:ring-accent',
        'ghost' => 'text-muted-foreground hover:bg-muted hover:text-foreground',
        'dark' => 'bg-foreground text-card hover:bg-foreground/90 focus:ring-foreground',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base font-semibold',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($tag === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
