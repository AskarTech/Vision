@props([
    'navigation' => [],
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
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
            @forelse($navigation as $group)
                @if(isset($group['label']))
                    <div class="mb-4">
                        <h3 class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                            {{ $group['label'] }}
                        </h3>
                        @foreach($group['items'] as $item)
                            <a href="{{ $item['url'] ?? '#' }}" 
                               @if(isset($item['active']))
                               :class="activeSection === '{{ $item['id'] }}' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5 hover:text-white'"
                               @else
                               class="text-slate-300 hover:bg-white/5 hover:text-white"
                               @endif
                               @click="activeSection = '{{ $item['id'] }}'; window.location.hash = '{{ $item['id'] }}'"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors">
                                <span class="text-lg">{!! $item['icon'] ?? '📄' !!}</span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                @else
                    {{-- Flat navigation without groups --}}
                    @foreach($navigation as $item)
                        <a href="{{ $item['url'] ?? '#' }}" 
                           :class="activeSection === '{{ $item['id'] }}' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5 hover:text-white'"
                           @click="activeSection = '{{ $item['id'] }}'; window.location.hash = '{{ $item['id'] }}'"
                           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors">
                            <span class="text-lg">{!! $item['icon'] ?? '📄' !!}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                @endif
            @empty
                {{-- Default navigation if none provided --}}
                @if($dashboardType === 'admin')
                    <div class="space-y-1">
                        <a href="#view-dashboard" :class="activeSection === 'view-dashboard' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-dashboard'; location.hash='view-dashboard'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>📊</span> لوحة الإدارة
                        </a>
                        <a href="#view-deposits" :class="activeSection === 'view-deposits' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-deposits'; location.hash='view-deposits'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>💰</span> الإيداعات
                        </a>
                        <a href="#view-customers" :class="activeSection === 'view-customers' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-customers'; location.hash='view-customers'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>👥</span> المستخدمون
                        </a>
                        <a href="#view-partners" :class="activeSection === 'view-partners' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-partners'; location.hash='view-partners'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>🤝</span> الشركاء
                        </a>
                        <a href="#view-inventory" :class="activeSection === 'view-inventory' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-inventory'; location.hash='view-inventory'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>📦</span> المخزون
                        </a>
                        <a href="#view-finance" :class="activeSection === 'view-finance' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='view-finance'; location.hash='view-finance'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>📈</span> المالية
                        </a>
                    </div>
                @else
                    <div class="space-y-1">
                        <a href="#seller-overview" :class="activeSection === 'seller-overview' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='seller-overview'; location.hash='seller-overview'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>📊</span> نظرة عامة
                        </a>
                        <a href="#seller-inventory" :class="activeSection === 'seller-inventory' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='seller-inventory'; location.hash='seller-inventory'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>📦</span> المخزون
                        </a>
                        <a href="#seller-orders" :class="activeSection === 'seller-orders' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='seller-orders'; location.hash='seller-orders'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>🛒</span> الطلبات
                        </a>
                        <a href="#seller-withdrawals" :class="activeSection === 'seller-withdrawals' ? 'bg-emerald-500/20 text-emerald-400' : 'text-slate-300 hover:bg-white/5'" @click.prevent="activeSection='seller-withdrawals'; location.hash='seller-withdrawals'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium">
                            <span>💸</span> السحوبات
                        </a>
                    </div>
                @endif
            @endforelse
        </nav>

        <!-- Footer -->
        <div class="border-t border-white/10 px-4 py-4">
            <div class="text-xs text-slate-500 text-center">
                © {{ date('Y') }} YemenWi-Fi Hub
            </div>
        </div>
    </div>
</aside>
