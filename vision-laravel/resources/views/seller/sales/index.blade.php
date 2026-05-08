<x-layouts.dashboard title="تحليلات المبيعات" dashboardType="seller">
    <x-ui.page-header title="ملخص المبيعات والمخزون"
        description="إحصائيات البطاقات المباعة والمتوفرة — تعكس نشاط نقاط البيع على شبكتك." />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <x-ui.metric-card label="البطاقات المباعة" :value="number_format($stats['sold'])" tone="teal"
            class="rounded-[1.5rem]" />
        <x-ui.metric-card label="قيمة البطاقات المباعة (تقريبية)"
            :value="number_format((float) $stats['revenue'], 2)" tone="blue" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="المخزون النشط" :value="number_format($stats['active'])" tone="amber"
            class="rounded-[1.5rem]" />
        <x-ui.metric-card label="محجوز مؤقتاً" :value="number_format($stats['reserved'])" tone="rose"
            class="rounded-[1.5rem]" />
    </div>
    <x-ui.panel title="استيراد البطاقات" class="mt-6 rounded-[1.5rem]"
        description="استخدم ملف CSV من صفحة إضافة بطاقات. دعم Excel قيد الإعداد — تصدير من جداولك إلى CSV حالياً.">
        <a href="{{ route('seller.inventory.create') }}" class="btn btn-primary btn-sm rounded-xl">الانتقال إلى رفع
            المخزون</a>
    </x-ui.panel>
</x-layouts.dashboard>
