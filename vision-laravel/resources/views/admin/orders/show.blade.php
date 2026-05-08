<x-layouts.dashboard title="تفاصيل الطلب" dashboardType="admin">
    <x-ui.page-header title="طلب #{{ $order->id }}" description="تفاصيل العناصر والدفع" />
    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif
    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="ملخص الطلب" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-300">
                <p>العميل: {{ $order->user?->name ?? '-' }}</p>
                <p>الحالة: <x-ui.status-badge :status="$order->status" /></p>
                <p>القناة: {{ $order->payment_channel }}</p>
                <p>الإجمالي: {{ number_format((float)$order->total_amount, 2) }}</p>
            </div>
            <div class="mt-4 flex flex-col gap-2 border-t border-white/10 pt-4">
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
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2 text-sm">
                        <span class="text-slate-300">{{ $item->package_name }}</span>
                        <span class="text-white">{{ number_format((float)$item->line_total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
