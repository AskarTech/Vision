<x-layouts.dashboard title="تدقيق السحوبات" dashboardType="admin">
    <x-ui.page-header title="سجل السحوبات" />
    <x-ui.panel>
        <div class="space-y-2">
            @forelse($withdrawals as $withdrawal)
                <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2 text-sm">
                    <span>{{ $withdrawal->seller?->name ?? '-' }}</span>
                    <span>{{ number_format((float)$withdrawal->amount, 2) }}</span>
                    <x-ui.status-badge :status="$withdrawal->status" />
                </div>
            @empty
                <p class="text-sm text-slate-400">لا توجد سحوبات.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
