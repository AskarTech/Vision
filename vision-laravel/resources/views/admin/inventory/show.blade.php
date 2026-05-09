<x-layouts.dashboard title="بطاقة #{{ $card->id }}" :description="$card->package?->name ?? ''" dashboardType="admin">
    <x-ui.panel title="تفاصيل الكود">
        <div class="grid gap-4 text-sm text-slate-700 md:grid-cols-2">
            <p><span class="font-semibold text-slate-900">الكود:</span> <span class="font-mono text-emerald-900">{{ $card->code }}</span></p>
            <p><span class="font-semibold text-slate-900">الحالة:</span> <x-ui.status-badge :status="$card->status" /></p>
            <p><span class="font-semibold text-slate-900">البائع:</span> {{ $card->seller?->name ?? '-' }}</p>
            <p><span class="font-semibold text-slate-900">الشبكة:</span> {{ $card->network?->name ?? '-' }}</p>
            <p><span class="font-semibold text-slate-900">السعر:</span> {{ number_format((float) $card->price, 2) }}</p>
            <p><span class="font-semibold text-slate-900">تاريخ الرفع:</span> {{ $card->uploaded_at?->format('Y-m-d H:i') ?? '-' }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
