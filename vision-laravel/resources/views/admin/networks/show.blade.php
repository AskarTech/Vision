<x-layouts.dashboard :title="$network->name" description="ملخص الشبكة والباقات" dashboardType="admin">
    <div class="grid gap-6 md:grid-cols-3">
        <x-ui.metric-card label="الحالة" :value="$network->status" tone="teal" />
        <x-ui.metric-card label="الباقات" :value="$network->packages->count()" tone="blue" />
        <x-ui.metric-card label="البطاقات" :value="$network->cards->count()" tone="amber" />
    </div>
</x-layouts.dashboard>
