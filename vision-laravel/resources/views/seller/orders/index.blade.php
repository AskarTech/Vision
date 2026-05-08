<x-layouts.dashboard title="طلبات البائع" dashboardType="seller">
    <x-ui.page-header title="الطلبات" description="آخر الطلبات المرتبطة بمخزونك" />
    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="إجمالي الطلبات" :value="$stats['total']" tone="slate" />
        <x-ui.metric-card label="مدفوعة" :value="$stats['paid']" tone="emerald" />
        <x-ui.metric-card label="معلقة" :value="$stats['pending']" tone="amber" />
        <x-ui.metric-card label="الإيراد" :value="number_format((float)$stats['revenue'], 2)" tone="blue" />
    </section>

    <x-ui.panel>
        <x-ui.filter-bar :action="route('seller.orders.index')">
            <input name="search" value="{{ request('search') }}" placeholder="بحث برقم الطلب أو العميل" class="input input-bordered input-sm w-full" />
            <select name="status" class="select select-bordered select-sm w-full">
                <option value="">كل الحالات</option>
                @foreach(['pending', 'paid', 'failed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input input-bordered input-sm w-full" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input input-bordered input-sm w-full" />
        </x-ui.filter-bar>

        <div class="mt-4 space-y-2">
            @forelse($orders as $order)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-white/10 px-3 py-3">
                    <div>
                        <p class="text-sm font-semibold text-white">#{{ $order->id }} - {{ $order->user?->name }}</p>
                        <p class="text-xs text-slate-400">{{ $order->created_at?->format('Y-m-d H:i') }} - {{ $order->payment_channel }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <p class="text-sm font-semibold text-white">{{ number_format((float)$order->total_amount, 2) }}</p>
                        <x-ui.status-badge :status="$order->status" />
                        <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-ghost btn-xs">تفاصيل</a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">لا توجد طلبات.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
