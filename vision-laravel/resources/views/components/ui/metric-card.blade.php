@props([
    'label' => null,
    'value' => null,
    'caption' => null,
    'tone' => 'teal',
])

@php
    $allowed = ['teal', 'blue', 'amber', 'rose', 'slate', 'emerald'];
    $tone = in_array($tone, $allowed) ? $tone : 'slate';
    $tone = $tone === 'emerald' ? 'teal' : $tone;
    $toneClass = 'tone-' . $tone;
@endphp

<div {{ $attributes->merge(['class' => 'metric-card-base rounded-3xl ' . $toneClass]) }}>
    @if ($label)
        <span class="mb-3 block text-sm text-slate-500">{{ $label }}</span>
    @endif

    @if ($value !== null)
        <div class="text-3xl font-black tracking-tight text-slate-800">{{ $value }}</div>
    @endif

    @if ($caption)
        <p class="mt-3 text-xs leading-6 text-slate-500">{{ $caption }}</p>
    @endif
</div>
