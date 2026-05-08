<x-layouts.dashboard title="تفاصيل الباقة" dashboardType="admin">
    <x-ui.page-header title="{{ $package->name }}" description="تفاصيل الباقة والمخزون المرتبط" />
    <div class="grid gap-6 md:grid-cols-4">
        <x-ui.metric-card label="السعر" :value="number_format((float) $package->price, 2)" tone="teal" />
        <x-ui.metric-card label="الدورية" :value="$package->period_type" tone="blue" />
        <x-ui.metric-card label="الحالة" :value="$package->status" tone="amber" />
        <x-ui.metric-card label="البطاقات" :value="$package->cards->count()" tone="slate" />
    </div>
</x-layouts.dashboard>
