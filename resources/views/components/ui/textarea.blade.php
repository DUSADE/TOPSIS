@props(['disabled' => false, 'label' => null, 'error' => null, 'rows' => 3])

<div class="space-y-1">
    @if($label)
        <label {{ $attributes->has('id') ? 'for='.$attributes->get('id') : '' }} class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <textarea {{ $disabled ? 'disabled' : '' }} rows="{{ $rows }}" {!! $attributes->merge(['class' => 'block w-full rounded-xl border-border bg-white text-foreground shadow-sm transition-all duration-200 focus:border-primary focus:ring-4 focus:ring-primary/10 sm:text-sm placeholder:text-muted-foreground/50 border-2 ' . ($error ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500/10' : '')]) !!}>{{ $slot }}</textarea>

    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
