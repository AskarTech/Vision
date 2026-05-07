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
    
    <!-- Modern Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/modern-theme.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>

<body class="min-h-screen bg-gray-50 font-sans" style="font-family: 'Cairo', sans-serif;">
    <style>[x-cloak]{display:none !important;}</style>
    
    <div x-data="{ 
            sidebarOpen: false, 
            activeSection: '{{ request()->segment(2) ?: 'dashboard' }}',
            openNotifications: false, 
            openUser: false 
         }" 
         class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <x-layout.sidebar :type="$dashboardType" />

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Logo & Title -->
                    <div class="flex items-center gap-3">
                        <div class="gradient-primary w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">YW</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ $eyebrow }}</p>
                            <h1 class="text-lg font-bold text-gray-900">{{ $title }}</h1>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="بحث..." 
                               class="modern-input w-64 pr-10" />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button @click="openNotifications = !openNotifications" 
                                class="modern-btn modern-btn-outline p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($recentAlerts->count() > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">
                                    {{ $recentAlerts->count() }}
                                </span>
                            @endif
                        </button>

                        <div x-show="openNotifications" x-cloak @click.outside="openNotifications=false"
                             class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-100">
                                <h4 class="font-bold text-gray-900">التنبيهات الأخيرة</h4>
                            </div>
                            <div class="max-h-64 overflow-auto">
                                @forelse($recentAlerts as $alert)
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $alert->code ?? 'تنبيه' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $alert->report_reason ?? 'مراجعة مطلوبة' }}</p>
                                            </div>
                                            <x-ui.badge tone="info">{{ $alert->status ?? 'جديد' }}</x-ui.badge>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-gray-500">لا توجد تنبيهات حديثة</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button @click="openUser = !openUser" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3b82f6&color=fff" 
                                 alt="avatar" class="w-8 h-8 rounded-lg" />
                            <span class="text-sm font-medium text-gray-700 hidden md:block">{{ auth()->user()->name ?? 'مسؤول' }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="openUser" x-cloak @click.outside="openUser=false"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div class="p-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'مسؤول' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">الإعدادات</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50 p-6">
                @if ($description)
                    <div class="mb-6">
                        <p class="text-gray-600">{{ $description }}</p>
                    </div>
                @endif
                
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    </div>

    @stack('scripts')
</body>
</html>
