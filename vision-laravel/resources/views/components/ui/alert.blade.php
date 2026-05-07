@props([
    'tone' => 'info',
])

@php
    $tones = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'error' => 'alert-error',
    ];

    $toneClass = $tones[$tone] ?? $tones['info'];
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $toneClass . ' border-0 shadow-lg rounded-lg']) }}>
    <span class="text-sm">{{ $slot }}</span>
</div>
