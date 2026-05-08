<x-layouts.dashboard title="تفاصيل الشبكة" dashboardType="admin">
    <x-ui.page-header title="{{ $network->name }}" />
    <div class="grid gap-6 md:grid-cols-3">
        <x-ui.metric-card label="الحالة" :value="$network->status" tone="teal" />
        <x-ui.metric-card label="الباقات" :value="$network->packages->count()" tone="blue" />
        <x-ui.metric-card label="البطاقات" :value="$network->cards->count()" tone="amber" />
    </div>
</x-layouts.dashboard>
