<x-layouts.dashboard title="محفظة التسوية"
    description="رصيد الشريك المرتبط بمدير الشبكة — السحوبات والمبيعات تنعكس هنا بعد الموافقة." dashboardType="seller">

    <section class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="الرصيد الحالي (تسوية)"
            :value="number_format((float) ($sellerWallet?->balance ?? 0), 2)" caption="ريال" tone="teal" />
        <x-ui.metric-card label="رصيد النقاط" :value="number_format((int) ($sellerWallet?->points_balance ?? 0))"
            tone="amber" />
        <x-ui.metric-card label="مجموع بيع البطاقات (مباع)"
            :value="number_format((float) ($grossSoldTotal ?? 0), 2)" caption="مجموع أسعار المباع" tone="blue" />
        <x-ui.metric-card label="صافٍ تقديري بعد العمولة"
            :value="number_format((float) ($estimatedNetAfterCommission ?? 0), 2)" caption="عمولة {{ number_format((float) ($commissionRate ?? 0), 2) }}٪ — لا يغني عن سجل المحفظة." tone="slate" />
    </section>

    <x-ui.panel title="ملاحظات تشغيلية" class="mb-8">
        <p class="text-sm leading-relaxed text-slate-600">اطلب السحب من قسم «السحوبات». الرصيد المعروض يعكس التسويات المعتمدة؛ الأرقام التقديرية للمساعدة التشغيلية فقط.</p>
    </x-ui.panel>

    <x-ui.panel title="آخر حركات المحفظة">
        <div class="space-y-2">
            @forelse ($sellerTransactions as $tx)
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                    <span class="text-sm text-slate-700">{{ $tx->description ?? $tx->type }}</span>
                    <span class="text-sm font-semibold text-slate-900">{{ number_format((float) $tx->amount, 2) }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-500">لا توجد حركات مسجلة بعد.</p>
            @endforelse
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
