@props([
    'title' => 'YemenWi-Fi Hub',
    'description' => null,
])

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen saas-layout-main font-sans">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('customer.dashboard') }}" class="text-lg font-black text-slate-800">YemenWi-Fi Hub</a>
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('customer.marketplace.index') }}" class="btn btn-ghost btn-sm">المتجر</a>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-ghost btn-sm">طلباتي</a>
                <a href="{{ route('customer.wallet.index') }}" class="btn btn-ghost btn-sm">المحفظة</a>
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-ghost btn-sm">الحساب</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline btn-sm">خروج</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="saas-content mx-auto max-w-7xl px-4 py-6 sm:px-6">
        <section class="tech-oriental-hero mb-6 rounded-[1.5rem] border border-slate-200/80 px-5 py-4">
            <p class="text-sm font-semibold text-slate-700">YemenWi-Fi Hub</p>
            <p class="mt-1 text-xs text-slate-500">Modern SaaS UI · Tech-Oriental touch</p>
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
