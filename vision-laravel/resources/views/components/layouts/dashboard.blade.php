@props([
    'title' => 'YemenWi-Fi Hub',
    'description' => null,
    'eyebrow' => 'YemenWi-Fi Hub',
])

@php
    $recentAlerts = $recentAlerts ?? collect();
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="night">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>

        <style>[x-cloak]{display:none !important;}</style>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 right-0 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl"></div>
    <body x-data="{ activeSection: location.hash ? location.hash.substring(1) : 'view-dashboard', openNotifications: false, openUser: false }" x-init="window.addEventListener('hashchange', () => activeSection = location.hash ? location.hash.substring(1) : 'view-dashboard')" class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans">
    </div>

    <!-- Mobile blocker (from legacy) -->
    <div
        class="mobile-blocker hidden fixed inset-0 z-60 bg-white flex-col items-center justify-center p-8 text-center lg:hidden">
        <div class="mb-icon-box bg-slate-50 w-20 h-20 rounded-lg grid place-items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-10 h-10 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25" />
            </svg>
        </div>
        <h2 class="mb-title text-slate-900 text-lg font-extrabold mb-2">تجربة مخصصة للكمبيوتر</h2>
        <p class="mb-desc text-slate-600 max-w-xs">عذراً، لوحة تحكم الأدمن مصممة لتعمل بكفاءة على شاشات سطح المكتب فقط.
        </p>
                        <li><a @click.prevent="activeSection='view-dashboard'; location.hash='view-dashboard'"
                                :class="activeSection==='view-dashboard' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-dashboard">لوحة الإدارة</a></li>
                        <li><a @click.prevent="activeSection='view-audit'; location.hash='view-audit'"
                                :class="activeSection==='view-audit' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-audit">المراجعات المالية</a></li>
                        <li><a @click.prevent="activeSection='view-deposits'; location.hash='view-deposits'"
                                :class="activeSection==='view-deposits' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-deposits">الإيداعات</a></li>
                        <li><a @click.prevent="activeSection='view-customers'; location.hash='view-customers'"
                                :class="activeSection==='view-customers' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-customers">المستخدمون</a></li>
                        <li><a @click.prevent="activeSection='view-partners'; location.hash='view-partners'"
                                :class="activeSection==='view-partners' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-partners">الشركاء</a></li>
                        <li><a @click.prevent="activeSection='view-managers'; location.hash='view-managers'"
                                :class="activeSection==='view-managers' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-managers">المدراء</a></li>
                        <li><a @click.prevent="activeSection='view-products'; location.hash='view-products'"
                                :class="activeSection==='view-products' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-products">المنتجات</a></li>
                        <li><a @click.prevent="activeSection='view-inventory'; location.hash='view-inventory'"
                                :class="activeSection==='view-inventory' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-inventory">المخزون</a></li>
                        <li><a @click.prevent="activeSection='view-disputes'; location.hash='view-disputes'"
                                :class="activeSection==='view-disputes' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-disputes">النزاعات</a></li>
                        <li><a @click.prevent="activeSection='view-finance'; location.hash='view-finance'"
                                :class="activeSection==='view-finance' ? 'bg-white/10 text-white' : 'text-slate-200 hover:bg-white/5'"
                                class="nav-item px-3 py-2 rounded-lg" href="#view-finance">المالية</a></li>
                            href="#view-audit">المراجعات المالية</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-deposits">الإيداعات</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-customers">المستخدمون</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-partners">الشركاء</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-managers">المدراء</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-products">المنتجات</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-inventory">المخزون</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-disputes">النزاعات</a></li>
                    <li><a class="nav-item px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5"
                            href="#view-finance">المالية</a></li>
                </ul>
            </nav>

            <div class="mt-auto text-sm text-slate-400">© {{ date('Y') }} YemenWi-Fi</div>
        </aside>

        <main class="relative mx-auto w-full max-w-375 px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
            <header class="mb-6 rounded-3xl border border-white/10 bg-white/5 p-4 shadow-2xl backdrop-blur">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-emerald-400 to-sky-500 font-black text-slate-950 shadow-lg">
                            YH</div>
                        <div>
                            <p class="text-sm text-slate-400">{{ $eyebrow }}</p>
                            <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                            @if ($description)
                                <p class="mt-1 max-w-3xl text-sm leading-7 text-slate-300">{{ $description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @isset($badge)
                            <div>{{ $badge }}</div>
                        @endisset
                    </div>
                </div>
            </header>

            {{ $slot }}
        </main>
    </div>

    <main class="relative mx-auto w-full max-w-375 px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
        <header class="mb-6 rounded-3xl border border-white/10 bg-white/5 p-4 shadow-2xl backdrop-blur">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-emerald-400 to-sky-500 font-black text-slate-950 shadow-lg">
                        YH</div>
                    <div>
                        <p class="text-sm text-slate-400">{{ $eyebrow }}</p>
                        <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                        @if ($description)
                            <p class="mt-1 max-w-3xl text-sm leading-7 text-slate-300">{{ $description }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="hidden md:flex items-center gap-2">
                        <label for="dashboard-search" class="sr-only">بحث</label>
                        <div class="relative">
                            <input id="dashboard-search" placeholder="ابحث عن طلب، بائع، مستخدم..."
                                class="rounded-full bg-white/3 placeholder:text-slate-400 px-4 py-2 pr-10 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-400" />
                            <button class="absolute inset-y-0 left-0 flex items-center pr-3 text-slate-300"
                                aria-hidden="true">🔍</button>
                        </div>
                    </div>

                    <!-- Quick actions -->
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="btn btn-ghost btn-sm text-slate-200">+ إنشاء</button>
                        <button class="btn btn-ghost btn-sm text-slate-200">تصدير</button>
                    </div>

                    <!-- Notifications & User -->
                    <div x-data="{ openNotifications: false, openUser: false }" class="flex items-center gap-2">
                        <div class="relative">
                            <button @click="openNotifications = !openNotifications"
                                class="btn btn-ghost btn-sm relative">
                                🔔
                                <span
                                    class="absolute -top-2 -left-2 inline-flex items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-xs font-bold text-white">{{ $recentAlerts->count() }}</span>
                            </button>

                            <div x-show="openNotifications" x-cloak @click.outside="openNotifications=false"
                                class="z-30 mt-2 w-80 rounded-lg border border-white/10 bg-white/5 p-3 shadow-lg">
                                <h4 class="text-sm font-bold text-white mb-2">التنبيهات الأخيرة</h4>
                                <div class="space-y-2 max-h-56 overflow-auto">
                                    @forelse($recentAlerts as $alert)
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm text-white">{{ $alert->code }}</p>
                                                <p class="text-xs text-slate-400">
                                                    {{ $alert->report_reason ?? 'مراجعة' }}</p>
                                            </div>
                                            <x-ui.badge tone="{{ $alert->status }}">{{ $alert->status }}</x-ui.badge>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-400">لا توجد تنبيهات حديثة.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button @click="openUser = !openUser"
                                class="flex items-center gap-2 rounded-full bg-white/3 px-3 py-1 text-sm">
                                <img src="https://www.gravatar.com/avatar?d=mp&s=32" alt="avatar"
                                    class="h-7 w-7 rounded-full" />
                                <span class="text-slate-100">{{ auth()->user()->name ?? 'مسؤول' }}</span>
                            </button>

                            <div x-show="openUser" x-cloak @click.outside="openUser=false"
                                class="z-30 mt-2 w-44 rounded-lg border border-white/10 bg-white/5 p-2 shadow-lg">
                                <a href="#" class="block px-3 py-2 text-sm text-slate-100">الإعدادات</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 text-sm text-slate-100">تسجيل خروج</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @isset($badge)
                <div class="mt-4">{{ $badge }}</div>
            @endisset
        </header>

        {{ $slot }}
    </main>

    @stack('scripts')
</body>

</html>
