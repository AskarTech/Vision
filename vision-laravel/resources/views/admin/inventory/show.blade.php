<x-layouts.dashboard title="تفاصيل البطاقة" dashboardType="admin">
    <x-ui.page-header title="بطاقة #{{ $card->id }}" description="{{ $card->package?->name }}" />
    <x-ui.panel>
        <div class="grid gap-4 md:grid-cols-2 text-sm text-slate-300">
            <p>الكود: <span class="text-white">{{ $card->code }}</span></p>
            <p>الحالة: <x-ui.status-badge :status="$card->status" /></p>
            <p>البائع: {{ $card->seller?->name ?? '-' }}</p>
            <p>الشبكة: {{ $card->network?->name ?? '-' }}</p>
            <p>السعر: {{ number_format((float)$card->price, 2) }}</p>
            <p>تاريخ الرفع: {{ $card->uploaded_at?->format('Y-m-d H:i') ?? '-' }}</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
