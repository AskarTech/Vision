@props([
    'label' => null,
    'value' => null,
    'caption' => null,
    'tone' => 'teal',
])

@php
    $allowed = ['teal', 'blue', 'amber', 'rose', 'slate'];
    $tone = in_array($tone, $allowed) ? $tone : 'slate';
    $toneClass = 'tone-' . $tone;
@endphp

<div {{ $attributes->merge(['class' => 'metric-card-base rounded-3xl ' . $toneClass]) }}>
    @if ($label)
        <span class="mb-3 block text-sm text-slate-400">{{ $label }}</span>
    @endif

    @if ($value !== null)
        <div class="text-3xl font-black tracking-tight text-white">{{ $value }}</div>
    @endif

    @if ($caption)
        <p class="mt-3 text-xs leading-6 text-slate-400">{{ $caption }}</p>
    @endif
</div>
