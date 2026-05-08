@props([
    'title' => 'YemenWi-Fi Hub',
    'description' => null,
    'eyebrow' => 'YemenWi-Fi Hub',
])

<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="night">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans" style="font-family: 'Tajawal', 'Cairo', ui-sans-serif, system-ui, sans-serif;">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 right-0 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 h-96 w-96 rounded-full bg-sky-400/15 blur-3xl"></div>
    </div>

    <main class="relative flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            @if (session('success'))
                <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
            @endif
            @if (session('error'))
                <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
            @endif
            <div
                class="mb-6 flex items-center gap-4 rounded-3xl border border-white/10 bg-white/5 p-5 shadow-2xl backdrop-blur">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-sky-500 font-black text-slate-950 shadow-lg">
                    YH</div>
                <div>
                    <p class="text-sm text-slate-400">{{ $eyebrow }}</p>
                    <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                    @if ($description)
                        <p class="mt-1 text-sm leading-7 text-slate-300">{{ $description }}</p>
                    @endif
                </div>
            </div>

            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
</body>

</html>
