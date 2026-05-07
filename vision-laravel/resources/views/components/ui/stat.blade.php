@props([
    'label' => null,
    'value' => null,
    'tone' => 'slate', // slate, teal, blue, amber, rose, emerald, violet
])

@php
$allowed = ['slate', 'teal', 'blue', 'amber', 'rose', 'emerald', 'violet'];
$tone = in_array($tone, $allowed) ? $tone : 'slate';

$toneClasses = [
    'slate' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
    'teal' => 'bg-teal-500/20 text-teal-300 border-teal-500/30',
    'blue' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
    'amber' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
    'rose' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
    'emerald' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
    'violet' => 'bg-violet-500/20 text-violet-300 border-violet-500/30',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ' . $toneClasses[$tone]]) }}>
    @if($label)
        {{ $label }}: 
    @endif
    {{ $value ?? $slot }}
</span>
