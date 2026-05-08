<x-layouts.dashboard title="تفاصيل طلب السحب" dashboardType="seller">
    <x-ui.page-header title="طلب #{{ $withdrawal->id }}" />
    <x-ui.panel>
        <div class="grid gap-3 md:grid-cols-2 text-sm text-slate-300">
            <p>المبلغ: {{ number_format((float)$withdrawal->amount, 2) }}</p>
            <p>الحالة: <x-ui.status-badge :status="$withdrawal->status" /></p>
            <p>البنك: {{ $withdrawal->bank_name }}</p>
            <p>رقم الحساب: {{ $withdrawal->account_number }}</p>
            <p>المستفيد: {{ $withdrawal->receiver_name }}</p>
            <p>تاريخ الطلب: {{ $withdrawal->created_at?->format('Y-m-d H:i') }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
