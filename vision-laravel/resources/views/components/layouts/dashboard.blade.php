@props([
    'title' => 'Vision',
    'description' => null,
    'eyebrow' => null,
    'dashboardType' => 'admin',
])

@php
    $recentAlerts = $recentAlerts ?? collect();
    $eyebrowResolved =
        $eyebrow ??
        ($dashboardType === 'admin'
            ? 'لوحة الإدارة'
            : ($dashboardType === 'seller'
                ? 'لوحة الشريك'
                : 'Vision'));
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen bg-[#f8fafc] font-sans text-slate-800 antialiased">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data="{
        sidebarOpen: false,
        activeSection: '{{ request()->segment(2) ?: 'view-dashboard' }}',
        openNotifications: false,
        openUser: false
    }" class="flex h-screen overflow-hidden">

        <x-layout.sidebar :dashboardType="$dashboardType" />

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header
                class="flex shrink-0 items-center justify-between border-b border-[#e2e8f0] bg-white px-5 py-4 shadow-sm">
                <div class="flex min-w-0 items-center gap-4">
                    <button type="button" @click="sidebarOpen = true"
                        class="rounded-[10px] p-2 hover:bg-slate-100 lg:hidden" aria-label="فتح القائمة">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex min-w-0 items-center gap-3">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#00bdae] to-[#7338a2] text-lg font-extrabold text-white shadow-[0_4px_12px_rgba(0,189,174,0.22)]">
                            V
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold text-slate-500">Vision</p>
                            <p class="truncate text-sm font-bold text-slate-800">{{ $eyebrowResolved }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="relative hidden md:block">
                        <input type="search" placeholder="بحث..."
                            class="dashboard-search-field input h-10 w-52 rounded-lg border border-[#e2e8f0] bg-[#f8fafc] pe-3 ps-10 text-sm text-slate-800 lg:w-64 focus:border-[#00bdae]" />
                        <svg class="pointer-events-none absolute start-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <div class="relative">
                        <button type="button" @click="openNotifications = !openNotifications"
                            class="btn btn-ghost btn-circle btn-sm border border-[#e2e8f0] bg-white text-slate-600 hover:bg-slate-50"
                            aria-label="التنبيهات">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($recentAlerts->count() > 0)
                                <span
                                    class="absolute -end-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-[#f94f51] px-1 text-[10px] font-bold text-white">
                                    {{ $recentAlerts->count() }}
                                </span>
                            @endif
                        </button>

                        <div x-show="openNotifications" x-cloak @click.outside="openNotifications=false"
                            x-transition:enter="transition ease-out duration-220"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
                            class="saas-modal-panel absolute start-0 z-50 mt-2 w-80">
                            <div class="border-b border-slate-200 p-4">
                                <h4 class="font-bold text-slate-800">التنبيهات الأخيرة</h4>
                            </div>
                            <div class="max-h-64 overflow-auto">
                                @forelse($recentAlerts as $alert)
                                    <div class="border-b border-slate-100 p-4 hover:bg-slate-50 last:border-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">
                                                    {{ $alert->code ?? 'تنبيه' }}</p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ $alert->report_reason ?? 'مراجعة مطلوبة' }}</p>
                                            </div>
                                            <x-ui.badge tone="info">{{ $alert->status ?? 'جديد' }}</x-ui.badge>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-slate-500">لا توجد تنبيهات حديثة</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button" @click="openUser = !openUser"
                            class="flex items-center gap-2 rounded-[10px] px-2 py-1.5 transition-colors hover:bg-slate-100 sm:px-3">
                            <x-ui.user-avatar :user="auth()->user()" />
                            <span
                                class="hidden max-w-[9rem] truncate text-sm font-semibold text-slate-700 md:inline">{{ auth()->user()->name ?? 'مستخدم' }}</span>
                            <svg class="hidden h-4 w-4 shrink-0 text-slate-500 sm:block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openUser" x-cloak @click.outside="openUser=false"
                            x-transition:enter="transition ease-out duration-220"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
                            class="saas-modal-panel absolute start-0 z-50 mt-2 w-52">
                            <div class="border-b border-slate-200 p-3">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ auth()->user()->name ?? 'مستخدم' }}</p>
                                <p class="truncate text-xs text-slate-500">
                                    {{ auth()->user()->email ?? auth()->user()->phone ?? '' }}</p>
                            </div>
                            @if (auth()->user()?->role === 'admin')
                                <a href="{{ route('admin.settings.index') }}"
                                    class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">الإعدادات</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-red-50">
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-[#f8fafc] p-8 sm:p-10">
                <header class="mb-8 flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-[26px] font-extrabold leading-tight text-slate-800">{{ $title }}</h1>
                        @if ($description)
                            <p class="mt-1 text-[15px] text-slate-600">{{ $description }}</p>
                        @endif
                    </div>
                    @isset($headerActions)
                        <div class="flex flex-wrap items-center gap-2">{{ $headerActions }}</div>
                    @endisset
                </header>

                @isset($badge)
                    <div class="mb-4 flex flex-wrap items-center gap-2">{{ $badge }}</div>
                @endisset

                {{ $slot }}
            </main>
        </div>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/35 lg:hidden"></div>
    </div>

    @stack('scripts')
</body>

</html>
