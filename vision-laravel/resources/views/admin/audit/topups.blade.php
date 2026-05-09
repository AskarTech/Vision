<x-layouts.dashboard title="سجل الإيداعات" description="تدقيق طلبات الشحن والإيداع" dashboardType="admin">
    <x-ui.panel title="حركات الإيداع">
        <div class="space-y-2">
            @forelse ($topups as $topup)
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm text-slate-800">
                    <span class="font-semibold">{{ $topup->user?->name ?? '-' }}</span>
                    <span class="font-mono font-bold text-emerald-800">{{ number_format((float) $topup->amount, 2) }}</span>
                    <x-ui.status-badge :status="$topup->status" />
                </div>
            @empty
                <p class="text-sm text-slate-500">لا توجد إيداعات.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $topups->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
