<x-layouts.dashboard title="سجل السحوبات" description="تدقيق طلبات سحب أرباح الشركاء" dashboardType="admin">
    <x-ui.panel title="حركات السحب">
        <div class="space-y-2">
            @forelse ($withdrawals as $withdrawal)
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm text-slate-800">
                    <span class="font-semibold">{{ $withdrawal->seller?->name ?? '-' }}</span>
                    <span class="font-mono font-bold text-emerald-800">{{ number_format((float) $withdrawal->amount, 2) }}</span>
                    <x-ui.status-badge :status="$withdrawal->status" />
                </div>
            @empty
                <p class="text-sm text-slate-500">لا توجد سحوبات.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
