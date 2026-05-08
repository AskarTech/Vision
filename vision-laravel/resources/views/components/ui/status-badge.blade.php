@props([
    'status' => 'pending',
])

@php
    $statusTone = match ($status) {
        'active', 'approved', 'paid', 'sold', 'completed' => 'emerald',
        'pending', 'reserved', 'disputed' => 'amber',
        'rejected', 'cancelled', 'disabled', 'suspended', 'failed' => 'rose',
        default => 'slate',
    };
@endphp

<x-ui.badge :tone="$statusTone">
    {{ $status }}
</x-ui.badge>
