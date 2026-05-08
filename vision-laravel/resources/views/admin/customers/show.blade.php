<x-layouts.dashboard title="تفاصيل العميل" dashboardType="admin">
    <x-ui.page-header title="{{ $customer->name }}" description="عرض بيانات العميل وسجل المحفظة والطلبات" />

    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="البيانات الأساسية" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-300">
                <p>البريد: {{ $customer->email ?? '-' }}</p>
                <p>الهاتف: {{ $customer->phone ?? '-' }}</p>
                <p>الحالة: <x-ui.status-badge :status="$customer->status" /></p>
                <p>الرصيد: {{ number_format((float) ($customer->wallet?->balance ?? 0), 2) }}</p>
            </div>
        </x-ui.panel>

        <x-ui.panel title="آخر الحركات" class="lg:col-span-2">
            <div class="space-y-3">
                @forelse($customer->walletTransactions->take(10) as $tx)
                    <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2">
                        <span class="text-sm text-slate-300">{{ $tx->description ?? '-' }}</span>
                        <span class="text-sm font-semibold text-white">{{ number_format((float) $tx->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">لا توجد حركات بعد.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
