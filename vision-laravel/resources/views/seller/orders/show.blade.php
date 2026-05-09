<x-layouts.dashboard title="طلب #{{ $order->id }}" description="تفاصيل الطلب المرتبط بمخزونك" dashboardType="seller">

    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="ملخص الطلب" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-700">
                <p><span class="font-bold text-slate-900">العميل:</span> {{ $order->user?->name ?? '-' }}</p>
                <p><span class="font-bold text-slate-900">القناة:</span> {{ $order->payment_channel }}</p>
                <p><span class="font-bold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$order->status" /></p>
                <p><span class="font-bold text-slate-900">الإجمالي:</span> {{ number_format((float) $order->total_amount, 2) }}</p>
                <p><span class="font-bold text-slate-900">التاريخ:</span> {{ $order->created_at?->format('Y-m-d H:i') }}</p>
            </div>
        </x-ui.panel>

        <x-ui.panel title="العناصر" class="lg:col-span-2">
            <div class="space-y-2">
                @forelse ($order->items as $item)
                    <div class="flex items-center justify-between rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $item->package_name }}</p>
                            <p class="text-xs text-slate-600">{{ $item->card?->code ?? $item->card_code ?? '-' }}</p>
                        </div>
                        <div class="text-end">
                            <p class="font-semibold text-slate-900">{{ number_format((float) $item->line_total, 2) }}</p>
                            <p class="text-xs text-slate-600">×{{ $item->quantity }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">لا توجد عناصر.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
