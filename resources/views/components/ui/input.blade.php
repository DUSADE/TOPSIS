@props(['disabled' => false, 'label' => null, 'error' => null])

<div class="space-y-1.5">
    @if($label)
        <label {{ $attributes->whereStartsWith('id') ? 'for=' . $attributes->get('id') : '' }}
            class="block text-sm font-semibold text-foreground/80 tracking-tight">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full rounded-xl border-border bg-white text-foreground shadow-sm transition-all duration-200 focus:border-primary focus:ring-4 focus:ring-primary/10 sm:text-sm placeholder:text-muted-foreground/50 border-2 ' . ($error ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500/10' : '')]) !!}>
    </div>

    @if($error)
        <p class="text-xs font-medium text-red-500 mt-1">{{ $error }}</p>
    @endif
</div>