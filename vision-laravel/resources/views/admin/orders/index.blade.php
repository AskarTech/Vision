<x-layouts.dashboard title="الطلبات" description="جميع طلبات الشراء وتصفيتها" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">الطلبات</x-ui.badge>
    </x-slot>

    <section class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="إجمالي الطلبات" :value="number_format($stats['total'] ?? 0)" caption="كل السجلات" tone="slate" />
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="قيد المعالجة" tone="amber" />
        <x-ui.metric-card label="مدفوعة" :value="number_format($stats['completed'] ?? 0)" caption="مكتملة" tone="teal" />
        <x-ui.metric-card label="ملغاة" :value="number_format($stats['cancelled'] ?? 0)" caption="أو مستردة" tone="rose" />
        <x-ui.metric-card label="إيراد مدفوع" :value="number_format((float) ($stats['revenue'] ?? 0), 2)" caption="ريال" tone="blue" />
    </section>

    <x-ui.panel title="سجل الطلبات" description="تصفية حسب الحالة والقناة والتاريخ">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-5 flex flex-wrap items-center gap-2">
            <select name="status" class="admin-filter-field">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="paid" @selected(request('status') === 'paid')>مدفوع</option>
                <option value="failed" @selected(request('status') === 'failed')>فاشل</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>ملغى</option>
            </select>
            <select name="payment_channel" class="admin-filter-field">
                <option value="">كل القنوات</option>
                <option value="platform_wallet" @selected(request('payment_channel') === 'platform_wallet')>محفظة المنصة</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.orders.index') }}" class="admin-outline-btn">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">العميل</th>
                        <th scope="col">المبلغ</th>
                        <th scope="col">القناة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="font-normal text-slate-600">#{{ $order->id }}</td>
                            <td class="text-slate-800">{{ $order->user?->name ?? '—' }}</td>
                            <td class="font-semibold text-emerald-700">{{ number_format((float) $order->total_amount, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $order->payment_channel }}</td>
                            <td><x-ui.badge tone="{{ $order->status === 'paid' ? 'emerald' : ($order->status === 'pending' ? 'amber' : 'rose') }}">{{ $order->status }}</x-ui.badge></td>
                            <td class="text-xs font-normal text-slate-600">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="admin-outline-btn">تفاصيل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-slate-500">لا توجد طلبات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
