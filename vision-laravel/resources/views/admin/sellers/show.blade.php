<x-layouts.dashboard title="تفاصيل البائع" dashboardType="admin">
    <x-ui.page-header title="{{ $seller->name }}" description="ملف البائع وأداءه التشغيلي" />
    <div class="grid gap-6 md:grid-cols-4">
        <x-ui.metric-card label="الحالة" :value="$seller->status" tone="teal" />
        <x-ui.metric-card label="الشبكات" :value="$seller->networks->count()" tone="blue" />
        <x-ui.metric-card label="الباقات" :value="$seller->packages->count()" tone="amber" />
        <x-ui.metric-card label="البطاقات" :value="$seller->cards->count()" tone="slate" />
    </div>
</x-layouts.dashboard>
