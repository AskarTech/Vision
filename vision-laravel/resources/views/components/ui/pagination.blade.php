@props([
    'links' => [],
    'on' => null,
])

@if(count($links) > 1)
<nav aria-label="Pagination" class="flex items-center justify-between gap-4">
    <div class="text-sm text-slate-400">
        Showing 
        <span class="font-medium text-white">{{ $links[0]['label'] ?? 1 }}</span>
        to 
        <span class="font-medium text-white">{{ end($links)['label'] ?? count($links) }}</span>
        of 
        <span class="font-medium text-white">{{ count($links) }}</span> results
    </div>
    
    <div class="flex items-center gap-2">
        {{-- Previous Button --}}
        @if(isset($links[0]) && $links[0]['url'])
            <a href="{{ $links[0]['url'] }}" 
               class="btn btn-ghost btn-sm text-slate-300 hover:text-white">
                السابق
            </a>
        @else
            <button disabled class="btn btn-ghost btn-sm opacity-50 cursor-not-allowed text-slate-500">
                السابق
            </button>
        @endif

        {{-- Page Numbers --}}
        <div class="hidden sm:flex items-center gap-1">
            @foreach($links as $index => $link)
                @if($index > 0 && $index < count($links) - 1)
                    @if(is_string($link))
                        <span class="px-3 py-1.5 text-sm text-slate-500">{{ $link }}</span>
                    @elseif(isset($link['active']) && $link['active'])
                        <span class="px-3 py-1.5 text-sm font-bold text-white bg-emerald-500/20 rounded-lg border border-emerald-500/30">
                            {{ $link['label'] }}
                        </span>
                    @elseif(isset($link['url']))
                        <a href="{{ $link['url'] }}" 
                           class="px-3 py-1.5 text-sm text-slate-300 hover:bg-white/5 rounded-lg transition-colors">
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endif
            @endforeach
        </div>

        {{-- Next Button --}}
        @if(isset($links[count($links) - 1]) && $links[count($links) - 1]['url'])
            <a href="{{ $links[count($links) - 1]['url'] }}" 
               class="btn btn-ghost btn-sm text-slate-300 hover:text-white">
                التالي
            </a>
        @else
            <button disabled class="btn btn-ghost btn-sm opacity-50 cursor-not-allowed text-slate-500">
                التالي
            </button>
        @endif
    </div>
</nav>
@endif
