@props([
    'label' => null,
    'hint' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if ($label)
        <label class="label px-0 pb-0 pt-0">
            <span class="label-text text-sm font-semibold text-slate-200">{{ $label }}</span>
        </label>
    @endif

    {{ $slot }}

    @if ($hint)
        <p class="text-xs leading-6 text-slate-400">{{ $hint }}</p>
    @endif
</div>
