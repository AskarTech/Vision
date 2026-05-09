<x-layouts.dashboard title="ملخص المبيعات والمخزون"
    description="إحصائيات البطاقات المباعة والمتوفرة — نشاط نقاط البيع على شبكتك." dashboardType="seller">

    <section class="vision-grid-stats">
        <x-ui.metric-card label="البطاقات المباعة" :value="number_format($stats['sold'])" tone="teal" />
        <x-ui.metric-card label="قيمة المبيعات (تقريبية)"
            :value="number_format((float) $stats['revenue'], 2)" caption="ريال" tone="blue" />
        <x-ui.metric-card label="المخزون النشط" :value="number_format($stats['active'])" tone="amber" />
        <x-ui.metric-card label="محجوز مؤقتاً" :value="number_format($stats['reserved'])" tone="rose" />
    </section>

    <x-ui.panel title="استيراد البطاقات"
        description="استخدم ملف CSV من صفحة رفع المخزون. ملفات Excel قيد الإعداد — صدّر إلى CSV من Excel أو Sheets حالياً.">
        <a href="{{ route('seller.inventory.create') }}" class="btn btn-primary btn-sm">الانتقال إلى رفع المخزون</a>
    </x-ui.panel>
</x-layouts.dashboard>
