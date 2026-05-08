@props([
    'action' => '',
])

<form method="GET" action="{{ $action }}" class="grid gap-3 rounded-[1.5rem] border border-slate-200/80 bg-white p-4 shadow-sm md:grid-cols-4">
    {{ $slot }}

    <div class="flex items-end gap-2">
        <button type="submit" class="btn btn-primary btn-sm">تطبيق</button>
        <a href="{{ $action }}" class="btn btn-ghost btn-sm">إعادة تعيين</a>
    </div>
</form>
