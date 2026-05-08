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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen font-sans" style="font-family: 'Cairo', sans-serif;">
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
            <header class="header-modern flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <p class="text-xs" style="color: var(--text-muted);">{{ $eyebrow }}</p>
                            <h1 class="text-lg font-bold logo-modern">{{ $title }}</h1>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="بحث..." class="input-modern w-64 pr-10" />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5" style="color: var(--text-muted);"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button @click="openNotifications = !openNotifications" class="btn-modern btn-outline p-2"
                            style="background: transparent; border: 1px solid var(--border-light);">
                            <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
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
                            class="absolute left-0 mt-2 w-80 rounded-xl shadow-xl border z-50"
                            style="background: var(--bg-card); border-color: var(--border-light);">
                            <div class="p-4 border-b" style="border-color: var(--border-light);">
                                <h4 class="font-bold" style="color: var(--text-primary);">التنبيهات الأخيرة</h4>
                            </div>
                            <div class="max-h-64 overflow-auto">
                                @forelse($recentAlerts as $alert)
                                    <div class="p-4 hover:bg-gray-50 border-b last:border-0"
                                        style="border-color: var(--border-light);">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-semibold" style="color: var(--text-primary);">
                                                    {{ $alert->code ?? 'تنبيه' }}</p>
                                                <p class="text-xs mt-1" style="color: var(--text-muted);">
                                                    {{ $alert->report_reason ?? 'مراجعة مطلوبة' }}</p>
                                            </div>
                                            <x-ui.badge tone="info">{{ $alert->status ?? 'جديد' }}</x-ui.badge>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm" style="color: var(--text-muted);">لا توجد
                                        تنبيهات حديثة</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button @click="openUser = !openUser"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors"
                            style="background: transparent;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3b82f6&color=fff"
                                alt="avatar" class="w-8 h-8 rounded-lg" />
                            <span class="text-sm font-medium hidden md:block"
                                style="color: var(--text-secondary);">{{ auth()->user()->name ?? 'مسؤول' }}</span>
                            <svg class="w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openUser" x-cloak @click.outside="openUser=false"
                            class="absolute left-0 mt-2 w-48 rounded-xl shadow-lg border z-50"
                            style="background: var(--bg-card); border-color: var(--border-light);">
                            <div class="p-3 border-b" style="border-color: var(--border-light);">
                                <p class="text-sm font-semibold" style="color: var(--text-primary);">
                                    {{ auth()->user()->name ?? 'مسؤول' }}</p>
                                <p class="text-xs" style="color: var(--text-muted);">
                                    {{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-50"
                                style="color: var(--text-secondary);">الإعدادات</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-red-50"
                                    style="color: var(--color-danger-600);">
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6" style="background: var(--bg-secondary);">
                @if ($description)
                    <div class="mb-6">
                        <p style="color: var(--text-secondary);">{{ $description }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 lg:hidden"
            style="background: rgba(0, 0, 0, 0.5);"></div>
    </div>

    @stack('scripts')
</body>

</html>
