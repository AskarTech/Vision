@props([
    'user',
    'sizeClass' => 'h-8 w-8',
])

@php
    $name = $user?->name ?? '?';
    $initial = mb_strtoupper(mb_substr(trim((string) $name), 0, 1, 'UTF-8'), 'UTF-8');
@endphp

<div {{ $attributes->merge([
    'class' =>
        $sizeClass .
        ' flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#00bdae] to-[#7338a2] text-sm font-extrabold text-white shadow-md',
]) }}
    aria-hidden="true">
    {{ $initial }}
</div>
