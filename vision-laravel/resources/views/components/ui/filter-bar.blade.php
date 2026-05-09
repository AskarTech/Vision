@props([
    'action' => '',
])

<form method="GET" action="{{ $action }}" class="grid gap-3 rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-[0_1px_3px_rgba(0,0,0,0.05)] md:grid-cols-4">
    {{ $slot }}

    <div class="flex items-end gap-2">
        <button type="submit" class="btn btn-primary btn-sm">تطبيق</button>
        <a href="{{ $action }}" class="vision-outline-btn">إعادة تعيين</a>
    </div>
</form>
