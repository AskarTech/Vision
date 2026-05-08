<x-layouts.dashboard title="مركز الإشعارات" description="طوابير تحتاج اطلاعاً سريعاً" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">تشغيلي</x-ui.badge></x-slot>

    <div class="grid gap-4 md:grid-cols-2">
        <a href="{{ route('admin.topups.index', ['status' => 'pending']) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-amber-400/40">
            <p class="text-sm text-slate-400">إيداعات معلقة</p>
            <p class="mt-2 text-3xl font-black text-amber-300">{{ number_format($counts['pending_topups'] ?? 0) }}</p>
            <p class="mt-3 text-xs text-emerald-400">انتقل إلى مراجعة الإيداعات ←</p>
        </a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-rose-400/40">
            <p class="text-sm text-slate-400">سحوبات معلقة</p>
            <p class="mt-2 text-3xl font-black text-rose-300">{{ number_format($counts['pending_withdrawals'] ?? 0) }}</p>
            <p class="mt-3 text-xs text-emerald-400">انتقل إلى السحوبات ←</p>
        </a>
        <a href="{{ route('admin.disputes.index') }}" class="rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-blue-400/40">
            <p class="text-sm text-slate-400">طلبات فاشلة (نزاعات)</p>
            <p class="mt-2 text-3xl font-black text-blue-300">{{ number_format($counts['open_disputes'] ?? 0) }}</p>
            <p class="mt-3 text-xs text-emerald-400">فتح وحدة النزاعات ←</p>
        </a>
        <a href="{{ route('admin.inventory.index', ['status' => 'reported']) }}" class="rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-purple-400/40">
            <p class="text-sm text-slate-400">بطاقات بها بلاغ</p>
            <p class="mt-2 text-3xl font-black text-purple-300">{{ number_format($counts['reported_cards'] ?? 0) }}</p>
            <p class="mt-3 text-xs text-emerald-400">عرض المخزون المصفّى ←</p>
        </a>
    </div>
</x-layouts.dashboard>
