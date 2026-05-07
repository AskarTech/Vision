@props([
    'tone' => 'neutral',
])

@php
    $tones = [
        'active' => 'badge-success',
        'completed' => 'badge-success',
        'pending' => 'badge-warning',
        'reserved' => 'badge-warning',
        'sold' => 'badge-info',
        'reported' => 'badge-error',
        'disabled' => 'badge-error',
        'draft' => 'badge-neutral',
        'inactive' => 'badge-neutral',
        'info' => 'badge-info',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'error' => 'badge-error',
        'neutral' => 'badge-neutral',
    ];

    $toneClass = $tones[$tone] ?? 'badge-neutral';
@endphp

<span
    {{ $attributes->merge(['class' => 'badge border-0 px-3 py-3 text-xs font-bold uppercase tracking-wide ' . $toneClass]) }}>
    {{ $slot }}
</span>
