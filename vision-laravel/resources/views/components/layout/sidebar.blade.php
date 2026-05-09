@props([
    'dashboardType' => 'admin',
])

@php
    $subtitle =
        $dashboardType === 'admin'
            ? 'لوحة الإدارة'
            : ($dashboardType === 'seller'
                ? 'لوحة الشريك'
                : 'Vision');
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
    {{ $attributes->merge([
        'class' =>
            'fixed inset-y-0 right-0 z-50 w-[280px] transform border-l border-[#e2e8f0] bg-white shadow-[1px_0_12px_rgba(15,23,42,0.06)] transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0',
    ]) }}
    x-cloak>
    <div class="flex h-full flex-col">
        <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-6">
            <div class="flex min-w-0 items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#00bdae] to-[#7338a2] text-lg font-extrabold text-white shadow-[0_4px_12px_rgba(0,189,174,0.22)]">
                    V
                </div>
                <div class="min-w-0">
                    <h2 class="truncate text-lg font-extrabold text-slate-800">Vision</h2>
                    <p class="truncate text-xs text-slate-500">{{ $subtitle }}</p>
                </div>
            </div>
            <button type="button" @click="sidebarOpen = false"
                class="rounded-[10px] p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="إغلاق القائمة">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-5">
            <x-layout.navigation :dashboard-type="$dashboardType" />
        </div>

        <div class="border-t border-[#e2e8f0] px-4 py-4">
            <p class="text-center text-xs text-slate-500">© {{ date('Y') }} شركة فيجن للاتصالات</p>
        </div>
    </div>
</aside>
