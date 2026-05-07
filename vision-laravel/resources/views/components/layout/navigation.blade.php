@props(['dashboardType' => 'admin'])

@php
    $navigation = app(\App\View\Components\DashboardNavigation::class, ['dashboardType' => $dashboardType]);
    $items = $navigation->getNavigationItems();
@endphp

<nav class="space-y-1" x-data="{ expanded: {} }">
    @foreach($items as $index => $item)
        @if(isset($item['sub']) && is_array($item['sub']))
            {{-- Navigation group with sub-items --}}
            <div class="mb-2">
                <button
                    type="button"
                    @click="expanded[{{ $index }}] = !expanded[{{ $index }}]"
                    class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors
                        {{ $navigation->isActive($item['route'] ?? '') 
                            ? 'bg-primary/10 text-primary' 
                            : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}"
                >
                    <div class="flex items-center gap-3">
                        @if(isset($item['icon']))
                            <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5" />
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" 
                         :class="expanded[{{ $index }}] ? 'rotate-90' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="expanded[{{ $index }}]" 
                     x-collapse
                     class="mt-1 ml-4 space-y-1 border-l-2 border-base-300 pl-2">
                    @foreach($item['sub'] as $subItem)
                        <a href="{{ route($subItem['route']) }}"
                           class="block px-3 py-2 text-sm rounded-lg transition-colors
                               {{ $navigation->isActive($subItem['route']) 
                                   ? 'bg-primary/10 text-primary font-medium' 
                                   : 'text-base-content/60 hover:bg-base-200 hover:text-base-content' }}">
                            {{ $subItem['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Single navigation item --}}
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors
                   {{ $navigation->isActive($item['route']) 
                       ? 'bg-primary/10 text-primary' 
                       : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                @if(isset($item['icon']))
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5" />
                @endif
                <span>{{ $item['label'] }}</span>
                
                @if(isset($item['badge']))
                    <span class="ml-auto badge badge-sm {{ $item['badge']['type'] ?? 'badge-neutral' }}">
                        {{ $item['badge']['value'] }}
                    </span>
                @endif
            </a>
        @endif
    @endforeach
</nav>
