<x-layouts.dashboard title="محفظة الشريك" dashboardType="seller">
    <x-ui.page-header title="محفظة التسوية"
        description="رصيد الشريك المرتبط بحساب مدير الشبكة — السحوبات والمبيعات تنعكس هنا بعد الموافقة." />
    <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="الرصيد الحالي (تسوية)"
            :value="number_format((float) ($sellerWallet?->balance ?? 0), 2)" tone="teal" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="رصيد النقاط" :value="number_format((int) ($sellerWallet?->points_balance ?? 0))"
            tone="amber" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="مجموع بيع البطاقات (مباع)"
            :value="number_format((float) ($grossSoldTotal ?? 0), 2)" caption="مجموع أسعار البطاقات المباعة" tone="blue" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="صافٍ تقديري بعد العمولة"
            :value="number_format((float) ($estimatedNetAfterCommission ?? 0), 2)" caption="حسب عمولة {{ number_format((float) ($commissionRate ?? 0), 2) }}٪ — لا يستبدل سجل المحفظة حتى تُقيد التسويات." tone="slate" class="rounded-[1.5rem]" />
        <x-ui.panel title="ملخص" class="lg:col-span-4 rounded-[1.5rem] shadow-[0_10px_25px_-5px_rgba(0,0,0,0.08)]">
            <p class="text-sm leading-7 text-slate-600">اطلب السحب من قسم «السحوبات». الرصيد المعروض في المحفظة يعكس التسويات المعتمدة؛ الأرقام التقديرية أعلاه للمساعدة التشغيلية فقط.</p>
        </x-ui.panel>
        <x-ui.panel title="آخر حركات المحفظة" class="lg:col-span-3 rounded-[1.5rem]">
            <div class="space-y-2">
                @forelse($sellerTransactions as $tx)
                    <div
                        class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200/80 bg-white px-3 py-2">
                        <span class="text-sm text-slate-600">{{ $tx->description ?? $tx->type }}</span>
                        <span
                            class="text-sm font-semibold text-slate-900">{{ number_format((float) $tx->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">لا توجد حركات مسجلة بعد.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
