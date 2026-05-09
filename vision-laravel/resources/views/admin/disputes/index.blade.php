<x-layouts.dashboard title="البلاغات" description="طلبات فاشلة تحتاج قرار إداري" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="warning">البلاغات</x-ui.badge></x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="مفتوحة (فاشلة)" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج قرار" tone="amber" />
        <x-ui.metric-card label="اليوم" :value="number_format($stats['today'] ?? 0)" caption="طلبات فاشلة اليوم" tone="rose" />
        <x-ui.metric-card label="محلولة / مدفوعة سابقاً" :value="number_format($stats['total_resolved'] ?? 0)" caption="مرجعية" tone="slate" />
        <x-ui.metric-card label="ملغاة / مستردة" :value="number_format($stats['refunded'] ?? 0)" caption="في الطلبات" tone="blue" />
    </section>

    <x-ui.panel title="قائمة النزاعات" description="استرداد أو رفض مع المبررات الإدارية">
        <form method="GET" action="{{ route('admin.disputes.index') }}" class="mb-5 flex flex-wrap items-center gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.disputes.index') }}" class="admin-outline-btn">إعادة تعيين</a>
        </form>

        <div class="space-y-4">
            @forelse ($disputes as $order)
                <div class="admin-list-card">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-800">طلب #{{ $order->id }} · {{ $order->user?->name ?? 'عميل' }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ number_format((float) $order->total_amount, 2) }} ريال · {{ $order->payment_channel }} · {{ $order->created_at?->format('Y-m-d H:i') }}</p>
                        </div>
                        <x-ui.badge tone="rose">{{ $order->status }}</x-ui.badge>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <form method="POST" action="{{ route('admin.disputes.refund', $order) }}" class="space-y-2 rounded-[10px] border border-emerald-200 bg-emerald-50/80 p-4">
                            @csrf
                            <label class="text-xs font-bold text-emerald-900">استرداد للعميل</label>
                            <textarea name="reason" required rows="3" class="textarea textarea-bordered admin-textarea min-h-[4.5rem] w-full text-sm" placeholder="سبب الاسترداد"></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-full">تأكيد الاسترداد</button>
                        </form>
                        <form method="POST" action="{{ route('admin.disputes.reject', $order) }}" class="space-y-2 rounded-[10px] border border-slate-200 bg-slate-50 p-4">
                            @csrf
                            <label class="text-xs font-bold text-slate-800">رفض النزاع (إبقاء مدفوع)</label>
                            <textarea name="reason" required rows="3" class="textarea textarea-bordered admin-textarea min-h-[4.5rem] w-full text-sm" placeholder="مبرر الرفض"></textarea>
                            <button type="submit" class="admin-outline-btn w-full">رفض النزاع</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-10 text-center text-slate-500">لا توجد نزاعات (طلبات فاشلة) حالياً.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $disputes->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
