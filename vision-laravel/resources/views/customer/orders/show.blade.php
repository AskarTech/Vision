<x-layouts.customer title="تفاصيل الطلب" description="عرض البطاقات التي تم شراؤها وتفاصيل الفاتورة.">
    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="بيانات الطلب" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-300">
                <p>رقم الطلب: #{{ $order->id }}</p>
                <p>الحالة: <x-ui.status-badge :status="$order->status" /></p>
                <p>قناة الدفع: {{ $order->payment_channel }}</p>
                <p>الإجمالي: {{ number_format((float)$order->total_amount, 2) }}</p>
                <p>التاريخ: {{ $order->created_at?->format('Y-m-d H:i') }}</p>
            </div>
        </x-ui.panel>

        <x-ui.panel title="العناصر المشتراة" class="lg:col-span-2">
            <div class="space-y-2">
                @foreach($order->items as $item)
                    <div class="rounded-xl border border-white/10 px-3 py-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">{{ $item->package_name }}</p>
                            <p class="text-sm text-slate-300">{{ number_format((float)$item->line_total, 2) }}</p>
                        </div>
                        <p class="mt-1 text-xs text-emerald-300">الكود: {{ $item->card?->code ?? $item->card_code ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        </x-ui.panel>
    </div>
</x-layouts.customer>
