<x-layouts.dashboard title="تدقيق الإيداعات" dashboardType="admin">
    <x-ui.page-header title="سجل الإيداعات" />
    <x-ui.panel>
        <div class="space-y-2">
            @forelse($topups as $topup)
                <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-2 text-sm">
                    <span>{{ $topup->user?->name ?? '-' }}</span>
                    <span>{{ number_format((float)$topup->amount, 2) }}</span>
                    <x-ui.status-badge :status="$topup->status" />
                </div>
            @empty
                <p class="text-sm text-slate-400">لا توجد إيداعات.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $topups->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
