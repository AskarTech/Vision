<x-layouts.dashboard title="الطلبات" description="آخر الطلبات المرتبطة بمخزونك" dashboardType="seller">

    <section class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="إجمالي الطلبات" :value="$stats['total']" tone="slate" />
        <x-ui.metric-card label="مدفوعة" :value="$stats['paid']" tone="teal" />
        <x-ui.metric-card label="معلقة" :value="$stats['pending']" tone="amber" />
        <x-ui.metric-card label="الإيراد" :value="number_format((float) $stats['revenue'], 2)" caption="ريال" tone="blue" />
    </section>

    <x-ui.panel title="سجل الطلبات" description="بحث وتصفية حسب الحالة والتاريخ">
        <form method="GET" action="{{ route('seller.orders.index') }}" class="mb-6 grid gap-3 md:grid-cols-2 lg:grid-cols-5">
            <input name="search" value="{{ request('search') }}" placeholder="رقم الطلب أو العميل"
                class="vision-filter-field lg:col-span-2" />
            <select name="status" class="vision-filter-field">
                <option value="">كل الحالات</option>
                @foreach (['pending', 'paid', 'failed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="vision-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="vision-filter-field" />
            <div class="flex flex-wrap gap-2 md:col-span-2 lg:col-span-5">
                <button type="submit" class="btn btn-primary btn-sm">تطبيق</button>
                <a href="{{ route('seller.orders.index') }}" class="vision-outline-btn">إعادة تعيين</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th scope="col">الطلب</th>
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
                            <td class="font-semibold text-slate-900">#{{ $order->id }}</td>
                            <td class="font-normal text-slate-700">{{ $order->user?->name ?? '—' }}</td>
                            <td class="font-semibold text-emerald-800">{{ number_format((float) $order->total_amount, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $order->payment_channel }}</td>
                            <td><x-ui.status-badge :status="$order->status" /></td>
                            <td class="text-xs font-normal text-slate-600">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('seller.orders.show', $order) }}" class="vision-outline-btn">تفاصيل</a></td>
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
