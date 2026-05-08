<x-layouts.dashboard title="النزاعات" description="طلبات فاشلة تحتاج قرار إداري" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="warning">النزاعات</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="مفتوحة (فاشلة)" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج قرار" tone="amber" />
        <x-ui.metric-card label="اليوم" :value="number_format($stats['today'] ?? 0)" caption="طلبات فاشلة اليوم" tone="rose" />
        <x-ui.metric-card label="محلولة / مدفوعة سابقاً" :value="number_format($stats['total_resolved'] ?? 0)" caption="مرجعية" tone="slate" />
        <x-ui.metric-card label="ملغاة / مستردة" :value="number_format($stats['refunded'] ?? 0)" caption="في الطلبات" tone="blue" />
    </section>

    <x-ui.panel>
        <form method="GET" action="{{ route('admin.disputes.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.disputes.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
        </form>

        <div class="space-y-6">
            @forelse ($disputes as $order)
                <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-white">طلب #{{ $order->id }} · {{ $order->user?->name ?? 'عميل' }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ number_format((float) $order->total_amount, 2) }} ريال · {{ $order->payment_channel }} · {{ $order->created_at?->format('Y-m-d H:i') }}</p>
                        </div>
                        <x-ui.badge tone="rose">{{ $order->status }}</x-ui.badge>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <form method="POST" action="{{ route('admin.disputes.refund', $order) }}" class="space-y-2 rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-3">
                            @csrf
                            <label class="text-xs font-semibold text-emerald-200">استرداد للعميل</label>
                            <textarea name="reason" required rows="2" class="textarea textarea-bordered w-full border-white/10 bg-slate-900 text-sm text-slate-100" placeholder="سبب الاسترداد"></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-full">تأكيد الاسترداد</button>
                        </form>
                        <form method="POST" action="{{ route('admin.disputes.reject', $order) }}" class="space-y-2 rounded-xl border border-slate-500/20 bg-slate-500/5 p-3">
                            @csrf
                            <label class="text-xs font-semibold text-slate-200">رفض النزاع (إبقاء مدفوع)</label>
                            <textarea name="reason" required rows="2" class="textarea textarea-bordered w-full border-white/10 bg-slate-900 text-sm text-slate-100" placeholder="مبرر الرفض"></textarea>
                            <button type="submit" class="btn btn-outline btn-sm w-full border-white/20 text-slate-100">رفض النزاع</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-8 text-center text-slate-400">لا توجد نزاعات (طلبات فاشلة) حالياً.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $disputes->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
