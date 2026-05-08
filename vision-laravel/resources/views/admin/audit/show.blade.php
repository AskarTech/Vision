<x-layouts.dashboard title="تفاصيل الحركة المالية" dashboardType="admin">
    <x-ui.page-header title="حركة #{{ $transaction->id }}" />
    <x-ui.panel>
        <div class="grid gap-3 md:grid-cols-2 text-sm text-slate-300">
            <p>المستخدم: {{ $transaction->user?->name ?? '-' }}</p>
            <p>النوع: {{ $transaction->type }}</p>
            <p>القناة: {{ $transaction->channel }}</p>
            <p>الحالة: <x-ui.status-badge :status="$transaction->status" /></p>
            <p>المبلغ: {{ number_format((float)$transaction->amount, 2) }}</p>
            <p>الرصيد بعد العملية: {{ number_format((float)$transaction->balance_after, 2) }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
