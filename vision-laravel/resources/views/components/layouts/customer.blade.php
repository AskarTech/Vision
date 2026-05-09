@props([
    'title' => 'Vision',
    'description' => null,
])

<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen saas-layout-main font-sans">
    @php
        $customerNav = config('navigation.customer', []);
        $user = auth()->user();
    @endphp

    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur" x-data="{ mobileNavOpen: false }">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <div class="flex min-w-0 flex-1 items-center gap-2">
                <button type="button"
                    class="btn btn-ghost btn-square shrink-0 lg:hidden"
                    @click="mobileNavOpen = true"
                    aria-label="فتح القائمة">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('customer.dashboard') }}" class="truncate text-lg font-black text-slate-800">
                    Vision
                </a>
            </div>

            <nav class="hidden flex-wrap items-center justify-end gap-1 lg:flex" aria-label="التنقل الرئيسي">
                @foreach ($customerNav as $item)
                    @if (isset($item['permission']) && $user && ! $user->can($item['permission']))
                        @continue
                    @endif
                    <a href="{{ route($item['route']) }}"
                        class="btn btn-ghost btn-sm whitespace-nowrap">{{ $item['label'] }}</a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">خروج</button>
                </form>
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0 lg:hidden">
                @csrf
                <button type="submit" class="btn btn-outline btn-xs">خروج</button>
            </form>
        </div>

        {{-- Mobile drawer (RTL: panel from start / physical right) --}}
        <div x-show="mobileNavOpen" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="mobileNavOpen = false"></div>
        <div x-show="mobileNavOpen" x-cloak x-transition:enter="transition transform duration-200 ease-out"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform duration-150 ease-in" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 flex w-[min(100vw-3rem,18rem)] flex-col border-l border-slate-200 bg-white shadow-xl lg:hidden"
            role="dialog" aria-modal="true" aria-label="قائمة الجوال">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <span class="font-bold text-slate-800">القائمة</span>
                <button type="button" class="btn btn-ghost btn-sm" @click="mobileNavOpen = false"
                    aria-label="إغلاق">✕</button>
            </div>
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-4" aria-label="روابط الجوال">
                @foreach ($customerNav as $item)
                    @if (isset($item['permission']) && $user && ! $user->can($item['permission']))
                        @continue
                    @endif
                    <a href="{{ route($item['route']) }}"
                        class="rounded-xl px-3 py-3 text-sm font-medium text-slate-800 hover:bg-slate-100"
                        @click="mobileNavOpen = false">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    <main class="saas-content mx-auto max-w-7xl px-4 py-6 sm:px-6">
        <section class="tech-oriental-hero mb-6 rounded-saas-card border border-slate-200/80 px-5 py-4">
            <p class="text-sm font-semibold text-slate-700">Vision</p>
            <p class="mt-1 text-xs text-slate-500">متجر البطاقات · دعم RTL · تجربة متجاوبة</p>
        </section>
        @if (session('success'))
            <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif
        @if (session('error'))
            <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
        @endif
        @if ($description)
            <p class="mb-4 text-sm text-slate-500">{{ $description }}</p>
        @endif
        {{ $slot }}
    </main>
</body>

</html>
