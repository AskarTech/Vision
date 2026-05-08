<x-layouts.dashboard title="تدقيق الطلبات" dashboardType="admin">
    <x-ui.page-header title="سجل الطلبات" />
    <x-ui.panel>
        <div class="space-y-2">
            @forelse($orders as $order)
                <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2 text-sm">
                    <span>#{{ $order->id }} - {{ $order->user?->name ?? '-' }}</span>
                    <span>{{ number_format((float)$order->total_amount, 2) }}</span>
                    <x-ui.status-badge :status="$order->status" />
                </div>
            @empty
                <p class="text-sm text-slate-400">لا توجد طلبات.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
