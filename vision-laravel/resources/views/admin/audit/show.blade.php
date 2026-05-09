<x-layouts.dashboard title="حركة #{{ $transaction->id }}" description="تفاصيل الحركة المالية" dashboardType="admin">
    <x-ui.panel title="بيانات الحركة">
        <div class="grid gap-3 text-sm text-slate-700 md:grid-cols-2">
            <p><span class="font-semibold text-slate-900">المستخدم:</span> {{ $transaction->user?->name ?? '-' }}</p>
            <p><span class="font-semibold text-slate-900">النوع:</span> {{ $transaction->type }}</p>
            <p><span class="font-semibold text-slate-900">القناة:</span> {{ $transaction->channel }}</p>
            <p><span class="font-semibold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$transaction->status" /></p>
            <p><span class="font-semibold text-slate-900">المبلغ:</span> {{ number_format((float) $transaction->amount, 2) }}</p>
            <p><span class="font-semibold text-slate-900">الرصيد بعد العملية:</span> {{ number_format((float) $transaction->balance_after, 2) }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
