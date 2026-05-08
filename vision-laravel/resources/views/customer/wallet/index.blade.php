<x-layouts.customer title="المحفظة" description="رصيد المحفظة وسجل الحركات المالية.">
    <section class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="الرصيد المتاح" :value="number_format((float)($wallet?->balance ?? 0), 2)" tone="emerald" />
        <x-ui.metric-card label="النقاط" :value="$wallet?->points_balance ?? 0" tone="blue" />
        <x-ui.metric-card label="الحالة" :value="$wallet?->status ?? 'inactive'" tone="slate" />
    </section>

    <x-ui.panel title="سجل المعاملات">
        @if(method_exists($transactions, 'count') && $transactions->count())
            <div class="space-y-2">
                @foreach($transactions as $tx)
                    <div class="flex items-center justify-between rounded-xl border border-white/10 px-3 py-3">
                        <div>
                            <p class="text-sm text-white">{{ $tx->description ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $tx->created_at?->format('Y-m-d H:i') }} - {{ $tx->channel }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-white">{{ number_format((float)$tx->amount, 2) }}</p>
                            <x-ui.status-badge :status="$tx->status" />
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $transactions->links() }}</div>
        @else
            <p class="text-sm text-slate-400">لا توجد معاملات حتى الآن.</p>
        @endif
    </x-ui.panel>
</x-layouts.customer>
