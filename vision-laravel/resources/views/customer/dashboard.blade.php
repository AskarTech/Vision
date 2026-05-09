<x-layouts.customer title="لوحة العميل" description="ملخص نشاطك الشرائي وحالة المحفظة.">
    <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="الطلبات" :value="$stats['orders']" tone="slate" />
        <x-ui.metric-card label="طلبات مدفوعة" :value="$stats['paid_orders']" tone="emerald" />
        <x-ui.metric-card label="رصيد المحفظة" :value="number_format($stats['wallet_balance'], 2)" tone="blue" />
        <x-ui.metric-card label="النقاط" :value="number_format($stats['points_balance'])" tone="teal" />
        <x-ui.metric-card label="إجمالي الإنفاق" :value="number_format($stats['spent_total'], 2)" tone="amber" />
    </section>

    <x-ui.panel title="آخر الطلبات">
        <div class="space-y-2">
            @forelse($recentOrders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-3 transition hover:bg-white/5">
                    <span class="text-sm text-white">طلب #{{ $order->id }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-300">{{ number_format((float) $order->total_amount, 2) }}</span>
                        <x-ui.status-badge :status="$order->status" />
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-400">لا توجد طلبات بعد.</p>
            @endforelse
        </div>
    </x-ui.panel>
</x-layouts.customer>
