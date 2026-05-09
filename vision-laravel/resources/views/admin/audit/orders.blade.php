<x-layouts.dashboard title="سجل الطلبات" description="تدقيق عمليات الشراء والقنوات" dashboardType="admin">
    <x-ui.panel title="حركات الطلبات">
        <div class="space-y-2">
            @forelse ($orders as $order)
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm text-slate-800">
                    <span class="font-semibold">#{{ $order->id }} — {{ $order->user?->name ?? '-' }}</span>
                    <span class="font-mono font-bold text-slate-900">{{ number_format((float) $order->total_amount, 2) }}</span>
                    <x-ui.status-badge :status="$order->status" />
                </div>
            @empty
                <p class="text-sm text-slate-500">لا توجد طلبات.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
