<x-layouts.dashboard :title="$customer->name" description="عرض بيانات العميل وسجل المحفظة والطلبات" dashboardType="admin">

    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="البيانات الأساسية" class="lg:col-span-1">
            <div class="space-y-2 text-sm text-slate-700">
                <p><span class="font-semibold text-slate-900">البريد:</span> {{ $customer->email ?? '-' }}</p>
                <p><span class="font-semibold text-slate-900">الهاتف:</span> {{ $customer->phone ?? '-' }}</p>
                <p><span class="font-semibold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$customer->status" /></p>
                <p><span class="font-semibold text-slate-900">الرصيد:</span> {{ number_format((float) ($customer->wallet?->balance ?? 0), 2) }}</p>
            </div>
        </x-ui.panel>

        <x-ui.panel title="آخر الحركات" class="lg:col-span-2">
            <div class="space-y-3">
                @forelse ($customer->walletTransactions->take(10) as $tx)
                    <div class="flex items-center justify-between rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                        <span class="text-sm text-slate-700">{{ $tx->description ?? '-' }}</span>
                        <span class="text-sm font-semibold text-slate-900">{{ number_format((float) $tx->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">لا توجد حركات بعد.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
