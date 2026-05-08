@props([
    'dashboardType' => 'admin',
])

<aside 
    :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
    class="sidebar-modern fixed inset-y-0 right-0 z-50 w-72 transform border-l border-slate-200/80 bg-white/95 transition-transform duration-300 ease-in-out backdrop-blur-xl lg:static lg:inset-auto lg:translate-x-0"
    x-cloak
>
    <div class="flex h-full flex-col">
        <!-- Logo Area -->
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl font-black text-white shadow-lg" style="background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);">
                    YW
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">YemenWi-Fi</h2>
                    <p class="text-xs text-slate-500">{{ $dashboardType === 'admin' ? 'لوحة الإدارة' : 'لوحة البائع' }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="text-slate-500 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6">
            <x-layout.navigation :dashboard-type="$dashboardType" />
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-200 px-4 py-4">
            <div class="text-center text-xs text-slate-500">
                © {{ date('Y') }} YemenWi-Fi Hub
            </div>
        </div>
    </div>
</aside>
