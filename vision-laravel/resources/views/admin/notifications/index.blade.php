<x-layouts.dashboard title="مركز الإشعارات" description="طوابير تحتاج اطلاعاً سريعاً" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">تشغيلي</x-ui.badge></x-slot>

    <div class="grid gap-6 md:grid-cols-2">
        <a href="{{ route('admin.topups.index', ['status' => 'pending']) }}" class="admin-list-card transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <p class="text-sm font-semibold text-slate-600">إيداعات معلقة</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ number_format($counts['pending_topups'] ?? 0) }}</p>
            <p class="mt-3 text-xs font-semibold text-[var(--vision-teal-dark)]">انتقل إلى مراجعة الإيداعات ←</p>
        </a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="admin-list-card transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <p class="text-sm font-semibold text-slate-600">سحوبات معلقة</p>
            <p class="mt-2 text-3xl font-extrabold text-rose-700">{{ number_format($counts['pending_withdrawals'] ?? 0) }}</p>
            <p class="mt-3 text-xs font-semibold text-[var(--vision-teal-dark)]">انتقل إلى السحوبات ←</p>
        </a>
        <a href="{{ route('admin.disputes.index') }}" class="admin-list-card transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <p class="text-sm font-semibold text-slate-600">طلبات فاشلة (بلاغات)</p>
            <p class="mt-2 text-3xl font-extrabold text-blue-800">{{ number_format($counts['open_disputes'] ?? 0) }}</p>
            <p class="mt-3 text-xs font-semibold text-[var(--vision-teal-dark)]">فتح البلاغات ←</p>
        </a>
        <a href="{{ route('admin.inventory.index', ['status' => 'reported']) }}" class="admin-list-card transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <p class="text-sm font-semibold text-slate-600">بطاقات بها بلاغ</p>
            <p class="mt-2 text-3xl font-extrabold text-purple-900">{{ number_format($counts['reported_cards'] ?? 0) }}</p>
            <p class="mt-3 text-xs font-semibold text-[var(--vision-teal-dark)]">عرض المخزون المصفّى ←</p>
        </a>
    </div>
</x-layouts.dashboard>
