<x-layouts.dashboard dashboard-type="seller">
    <x-slot name="title">@yield('title')</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-base-content">{{ $title ?? 'الوحدة' }}</h1>
        </div>

        <x-ui.panel>
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🚧</div>
                <h3 class="text-lg font-semibold text-base-content mb-2">قيد التطوير</h3>
                <p class="text-base-content/60">هذه الوحدة ستكون متاحة قريباً</p>
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
