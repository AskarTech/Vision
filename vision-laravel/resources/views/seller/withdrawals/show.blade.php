<x-layouts.dashboard title="طلب سحب #{{ $withdrawal->id }}" description="تفاصيل الطلب والحالة" dashboardType="seller">

    <x-ui.panel title="التفاصيل">
        <div class="grid gap-3 text-sm text-slate-700 md:grid-cols-2">
            <p><span class="font-bold text-slate-900">المبلغ:</span> {{ number_format((float) $withdrawal->amount, 2) }} ريال</p>
            <p><span class="font-bold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$withdrawal->status" /></p>
            <p><span class="font-bold text-slate-900">البنك:</span> {{ $withdrawal->bank_name }}</p>
            <p><span class="font-bold text-slate-900">رقم الحساب:</span> {{ $withdrawal->account_number }}</p>
            <p><span class="font-bold text-slate-900">المستفيد:</span> {{ $withdrawal->receiver_name }}</p>
            <p><span class="font-bold text-slate-900">تاريخ الطلب:</span> {{ $withdrawal->created_at?->format('Y-m-d H:i') }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
