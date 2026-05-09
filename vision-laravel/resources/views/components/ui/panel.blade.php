@props([
    'title' => null,
    'description' => null,
])

<section
    {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-[#e2e8f0] bg-white shadow-[0_1px_3px_rgba(0,0,0,0.05)]']) }}>
    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-[#e2e8f0] bg-white px-6 py-5">
        <div>
            @if ($title)
                <h2 class="text-base font-extrabold text-slate-800">{{ $title }}</h2>
            @endif

            @if ($description)
                <p class="mt-1 text-sm text-slate-600">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
        @endisset
    </div>

    <div class="bg-white px-6 py-6">
        {{ $slot }}
    </div>
</section>
