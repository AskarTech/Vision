@props([
    'title' => 'YemenWi-Fi Hub',
    'description' => null,
    'eyebrow' => 'YemenWi-Fi Hub',
    'dashboardType' => 'admin',
])

@php
    $recentAlerts = $recentAlerts ?? collect();
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen saas-layout-main font-sans">
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

        <!-- Sidebar -->
        <x-layout.sidebar :type="$dashboardType" class="sidebar-modern" />

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="header-modern flex items-center justify-between border-b border-slate-200/80 bg-white/90 px-6 py-4 backdrop-blur">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" class="lg:hidden rounded-xl p-2 hover:bg-slate-100">
                        <svg class="h-6 w-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Logo & Title -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg"
                            style="background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);">
                            <span class="text-white font-bold text-lg">YW</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">{{ $eyebrow }}</p>
                            <h1 class="logo-modern text-lg font-bold text-slate-800">{{ $title }}</h1>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="بحث..." class="input input-bordered w-64 rounded-xl border-slate-200 pr-10" />
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button @click="openNotifications = !openNotifications" class="btn btn-outline rounded-full p-2">
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($recentAlerts->count() > 0)
                                <span
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">
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
                            class="saas-modal-panel absolute left-0 z-50 mt-2 w-80">
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
                                    <div class="p-4 text-center text-sm text-slate-500">لا توجد
                                        تنبيهات حديثة</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button @click="openUser = !openUser"
                            class="flex items-center gap-2 rounded-xl px-3 py-2 transition-colors hover:bg-slate-100">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3b82f6&color=fff"
                                alt="avatar" class="w-8 h-8 rounded-lg" />
                            <span class="hidden text-sm font-medium text-slate-700 md:block">{{ auth()->user()->name ?? 'مسؤول' }}</span>
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
                            class="saas-modal-panel absolute left-0 z-50 mt-2 w-48">
                            <div class="border-b border-slate-200 p-3">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ auth()->user()->name ?? 'مسؤول' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ auth()->user()->email ?? '' }}</p>
                            </div>
                            @if(auth()->user()?->role === 'admin')
                                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">الإعدادات</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-left text-sm text-red-500 hover:bg-red-50">
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="saas-content flex-1 overflow-auto p-6">
                <section class="tech-oriental-hero mb-6 rounded-[1.5rem] border border-slate-200/80 px-5 py-4">
                    <p class="text-sm font-semibold text-slate-700">YemenWi-Fi Hub</p>
                    <p class="mt-1 text-xs text-slate-500">Modern SaaS UI · Tech-Oriental touch</p>
                </section>
                @if ($description)
                    <div class="mb-6">
                        <p class="text-slate-600">{{ $description }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/35 lg:hidden"></div>
    </div>

    @stack('scripts')
</body>

</html>
