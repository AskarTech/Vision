@props(['dashboardType' => 'admin'])

@php
    $navigation = app(\App\View\Components\DashboardNavigation::class, ['dashboardType' => $dashboardType]);
    $items = $navigation->getNavigationItems();

    $icons = [
        'home' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5L12 3l9 7.5M5 9.75V21h14V9.75"/></svg>',
        'banknotes' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 7.5h19.5v9H2.25v-9zM6 7.5v9m12-9v9M9 10.5h6M9 13.5h3"/></svg>',
        'users' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0"/></svg>',
        'store' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 9h16.5M5.25 9l1.5-4.5h10.5L18.75 9M6 9v10.5h12V9"/></svg>',
        'wifi' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 16.5a6 6 0 017.5 0M5.25 13.5a10.5 10.5 0 0113.5 0M2.25 10.5a15 15 0 0119.5 0M12 19.5h.01"/></svg>',
        'cube' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2.25l8.25 4.5v10.5L12 21.75l-8.25-4.5V6.75L12 2.25zM12 12l8.25-4.5M12 12L3.75 7.5M12 12v9.75"/></svg>',
        'inbox' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5h18v9H3v-9zM7.5 7.5L9 12h6l1.5-4.5"/></svg>',
        'shopping-cart' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 3h2.25l2.1 10.5h10.8l1.8-7.5H6.3M9 20.25a.75.75 0 100-1.5.75.75 0 000 1.5zM17.25 20.25a.75.75 0 100-1.5.75.75 0 000 1.5z"/></svg>',
        'arrow-down-tray' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v10m0 0l-4-4m4 4l4-4M4.5 15.75v3.75h15v-3.75"/></svg>',
        'exclamation-triangle' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.29 3.86l-8.1 14.03A1.5 1.5 0 003.48 20.25h17.04a1.5 1.5 0 001.29-2.36l-8.1-14.03a1.5 1.5 0 00-2.62 0zM12 9v4.5m0 3.75h.01"/></svg>',
        'chart-bar' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 19.5h15M7.5 16.5v-6M12 16.5v-9M16.5 16.5v-4.5"/></svg>',
        'document-text' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 3.75h6l4.5 4.5v12H7.5v-16.5zM9.75 12h4.5M9.75 15h4.5"/></svg>',
        'cog-6-tooth' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5zM4.5 12a7.5 7.5 0 0115 0 7.5 7.5 0 01-15 0z"/></svg>',
        'chart-pie' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.25 3.75a8.25 8.25 0 108.25 8.25h-8.25V3.75zM12.75 3.75V12h8.25"/></svg>',
        'wallet' =>
            '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 7.5h16.5v9H3.75v-9zM15.75 12h3"/></svg>',
    ];
@endphp

<nav class="space-y-1" x-data="{ expanded: {} }">
    @foreach ($items as $index => $item)
        @if (isset($item['sub']) && is_array($item['sub']))
            {{-- Navigation group with sub-items --}}
            <div class="mb-2">
                <button type="button" @click="expanded[{{ $index }}] = !expanded[{{ $index }}]"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-[10px] transition-colors
                        {{ $navigation->isActive($item['route'] ?? '')
                            ? 'vision-nav-active'
                            : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <div class="flex items-center gap-3">
                        @if (isset($item['icon']) && isset($icons[$item['icon']]))
                            <span class="w-5 h-5 text-current [&>svg]:w-5 [&>svg]:h-5">{!! $icons[$item['icon']] !!}</span>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="expanded[{{ $index }}] ? 'rotate-90' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="expanded[{{ $index }}]" x-collapse
                    class="mt-1 ml-4 space-y-1 border-l-2 border-base-300 pl-2">
                    @foreach ($item['sub'] as $subItem)
                        <a href="{{ route($subItem['route']) }}"
                            class="block px-4 py-2.5 text-sm rounded-[10px] transition-colors
                               {{ $navigation->isActive($subItem['route'])
                                   ? 'vision-nav-active font-medium'
                                   : 'text-base-content/60 hover:bg-base-200 hover:text-base-content' }}">
                            {{ $subItem['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Single navigation item --}}
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-[10px] transition-colors
                   {{ $navigation->isActive($item['route'])
                       ? 'vision-nav-active'
                       : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                @if (isset($item['icon']) && isset($icons[$item['icon']]))
                    <span class="w-5 h-5 text-current [&>svg]:w-5 [&>svg]:h-5">{!! $icons[$item['icon']] !!}</span>
                @endif
                <span>{{ $item['label'] }}</span>

                @if (isset($item['badge']))
                    <span class="ml-auto badge badge-sm {{ $item['badge']['type'] ?? 'badge-neutral' }}">
                        {{ $item['badge']['value'] }}
                    </span>
                @endif
            </a>
        @endif
    @endforeach
</nav>
