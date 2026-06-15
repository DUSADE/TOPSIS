@props(['variant' => null, 'color' => null])

@php
    $selected = $color ?? $variant ?? 'gray';
    $variants = [
        'gray' => 'bg-muted text-muted-foreground border-transparent',
        'primary' => 'bg-primary/10 text-primary border-primary/20',
        'secondary' => 'bg-secondary/10 text-secondary border-secondary/20',
        'accent' => 'bg-accent/10 text-accent border-accent/20',
        'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'green' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'danger' => 'bg-red-100 text-red-800 border-red-200',
        'red' => 'bg-red-100 text-red-800 border-red-200',
        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
    ];

    $class = "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border " . ($variants[$selected] ?? $variants['gray']);
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
