<x-layouts.dashboard title="طلب #{{ $order->id }}" description="تفاصيل العناصر والدفع" dashboardType="admin">
    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif
    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="ملخص الطلب" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-700">
                <p><span class="font-semibold text-slate-900">العميل:</span> {{ $order->user?->name ?? '-' }}</p>
                <p><span class="font-semibold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$order->status" /></p>
                <p><span class="font-semibold text-slate-900">القناة:</span> {{ $order->payment_channel }}</p>
                <p><span class="font-semibold text-slate-900">الإجمالي:</span> {{ number_format((float) $order->total_amount, 2) }}</p>
            </div>
            <div class="mt-4 flex flex-col gap-2 border-t border-[#e2e8f0] pt-4">
                @if ($order->status === 'pending')
                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('إلغاء هذا الطلب؟');">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm w-full">إلغاء الطلب</button>
                    </form>
                @endif
                @if ($order->status === 'paid' && $order->payment_channel === 'platform_wallet')
                    <form method="POST" action="{{ route('admin.orders.refund', $order) }}" onsubmit="return confirm('استرداد كامل للعميل وإرجاع البطاقات للمخزون؟');">
                        @csrf
                        <button type="submit" class="btn btn-error btn-sm w-full">استرداد إداري</button>
                    </form>
                @endif
            </div>
        </x-ui.panel>
        <x-ui.panel title="العناصر" class="lg:col-span-2">
            <div class="space-y-2">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm">
                        <span class="text-slate-700">{{ $item->package_name }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format((float) $item->line_total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
