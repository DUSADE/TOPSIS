@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center space-x-3 px-4 py-2.5 rounded-2xl bg-primary/12 text-primary font-semibold border border-primary/20 shadow-sm transition-all duration-200'
        : 'flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-stone-400 hover:bg-white/5 hover:text-white transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
