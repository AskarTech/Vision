<x-layouts.dashboard title="تفاصيل الطلب" dashboardType="seller">
    <x-ui.page-header title="طلب #{{ $order->id }}" description="تفاصيل الطلب المرتبط بمخزونك" />

    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="ملخص الطلب" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-300">
                <p>العميل: {{ $order->user?->name ?? '-' }}</p>
                <p>القناة: {{ $order->payment_channel }}</p>
                <p>الحالة: <x-ui.status-badge :status="$order->status" /></p>
                <p>الإجمالي: {{ number_format((float)$order->total_amount, 2) }}</p>
                <p>التاريخ: {{ $order->created_at?->format('Y-m-d H:i') }}</p>
            </div>
        </x-ui.panel>

        <x-ui.panel title="العناصر" class="lg:col-span-2">
            <div class="space-y-2">
                @forelse($order->items as $item)
                    <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2 text-sm">
                        <div>
                            <p class="text-white">{{ $item->package_name }}</p>
                            <p class="text-xs text-slate-400">{{ $item->card?->code ?? $item->card_code ?? '-' }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-white">{{ number_format((float)$item->line_total, 2) }}</p>
                            <p class="text-xs text-slate-400">x{{ $item->quantity }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">لا توجد عناصر.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
