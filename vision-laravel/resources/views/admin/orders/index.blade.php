<x-layouts.dashboard title="الطلبات" description="جميع طلبات الشراء وتصفيتها" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">الطلبات</x-ui.badge>
    </x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="إجمالي الطلبات" :value="number_format($stats['total'] ?? 0)" caption="كل السجلات" tone="slate" />
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="قيد المعالجة" tone="amber" />
        <x-ui.metric-card label="مدفوعة" :value="number_format($stats['completed'] ?? 0)" caption="مكتملة" tone="teal" />
        <x-ui.metric-card label="ملغاة" :value="number_format($stats['cancelled'] ?? 0)" caption="أو مستردة" tone="rose" />
        <x-ui.metric-card label="إيراد مدفوع" :value="number_format((float) ($stats['revenue'] ?? 0), 2)" caption="ريال" tone="blue" />
    </section>

    <x-ui.panel>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
            <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="paid" @selected(request('status') === 'paid')>مدفوع</option>
                <option value="failed" @selected(request('status') === 'failed')>فاشل</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>ملغى</option>
            </select>
            <select name="payment_channel" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                <option value="">كل القنوات</option>
                <option value="platform_wallet" @selected(request('payment_channel') === 'platform_wallet')>محفظة المنصة</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">#</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">العميل</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">المبلغ</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">القناة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">التاريخ</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-white/5">
                            <td class="px-4 py-3 text-sm text-slate-300">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-sm text-white">{{ $order->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-emerald-300">{{ number_format((float) $order->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $order->payment_channel }}</td>
                            <td class="px-4 py-3"><x-ui.badge tone="{{ $order->status === 'paid' ? 'emerald' : ($order->status === 'pending' ? 'amber' : 'rose') }}">{{ $order->status }}</x-ui.badge></td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-xs text-slate-300">تفاصيل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">لا توجد طلبات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
