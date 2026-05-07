@props([
    'dashboardType' => 'admin',
])

<aside 
    :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 right-0 z-50 w-72 transform bg-slate-900/95 border-l border-white/10 transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 backdrop-blur-xl"
    x-cloak
>
    <div class="flex h-full flex-col">
        <!-- Logo Area -->
        <div class="flex items-center justify-between px-6 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-sky-500 font-black text-slate-950 shadow-lg">
                    YH
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">YemenWi-Fi</h2>
                    <p class="text-xs text-slate-400">{{ $dashboardType === 'admin' ? 'لوحة الإدارة' : 'لوحة البائع' }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
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
        <div class="border-t border-white/10 px-4 py-4">
            <div class="text-xs text-slate-500 text-center">
                © {{ date('Y') }} YemenWi-Fi Hub
            </div>
        </div>
    </div>
</aside>
