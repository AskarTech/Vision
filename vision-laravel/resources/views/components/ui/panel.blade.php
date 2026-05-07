@props([
    'title' => null,
    'description' => null,
])

<section
    {{ $attributes->merge(['class' => 'overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur']) }}>
    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-white/10 px-5 py-4">
        <div>
            @if ($title)
                <h2 class="text-lg font-bold text-white">{{ $title }}</h2>
            @endif

            @if ($description)
                <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex items-center gap-2">{{ $actions }}</div>
        @endisset
    </div>

    <div class="px-5 py-5">
        {{ $slot }}
    </div>
</section>
