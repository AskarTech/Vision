<x-layouts.customer title="طلباتي" description="تتبع الطلبات وحالة كل عملية شراء.">
    <x-ui.panel>
        <x-ui.filter-bar :action="route('customer.orders.index')">
            <select name="status" class="select select-bordered select-sm w-full">
                <option value="">كل الحالات</option>
                @foreach(['pending', 'paid', 'failed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </x-ui.filter-bar>

        <div class="mt-4 space-y-2">
            @forelse($orders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-3 hover:bg-white/5">
                    <div>
                        <p class="text-sm font-semibold text-white">طلب #{{ $order->id }}</p>
                        <p class="text-xs text-slate-400">{{ $order->created_at?->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-300">{{ number_format((float)$order->total_amount, 2) }}</span>
                        <x-ui.status-badge :status="$order->status" />
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-400">لا توجد طلبات لعرضها.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    </x-ui.panel>
</x-layouts.customer>
