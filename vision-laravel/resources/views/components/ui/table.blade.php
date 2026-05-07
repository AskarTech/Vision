@props([
    'title' => null,
    'description' => null,
    'actions' => null,
])

<div class="rounded-2xl border border-white/10 bg-slate-950/40 p-5 shadow-xl">
    @if($title || $description)
    <div class="mb-4 flex items-start justify-between gap-4">
        <div>
            @if($title)
            <h3 class="text-lg font-bold text-white">{{ $title }}</h3>
            @endif
            @if($description)
            <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
            @endif
        </div>
        @if($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
        @endif
    </div>
    @endif

    <div class="{{ isset($title) || isset($description) ? '' : '' }}">
        {{ $slot }}
    </div>
</div>
