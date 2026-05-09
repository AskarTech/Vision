@props([
    'title' => 'Vision',
    'description' => null,
    'eyebrow' => 'Vision',
])

<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen font-sans antialiased">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="pointer-events-none fixed inset-0 overflow-hidden bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#334155]"
        aria-hidden="true"></div>

    <main class="relative flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            @if (session('success'))
                <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
            @endif
            @if (session('error'))
                <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
            @endif

            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
</body>

</html>
