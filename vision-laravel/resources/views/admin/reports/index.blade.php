<x-layouts.dashboard title="التقارير" description="ملخص مالي وسوقي للفترة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">التقارير</x-ui.badge></x-slot>

    <x-ui.panel class="mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-2">
            <div>
                <label class="mb-1 block text-xs text-slate-400">من</label>
                <input type="date" name="date_from" value="{{ \Illuminate\Support\Carbon::parse($dateFrom)->format('Y-m-d') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-400">إلى</label>
                <input type="date" name="date_to" value="{{ \Illuminate\Support\Carbon::parse($dateTo)->format('Y-m-d') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            </div>
            <button type="submit" class="btn btn-primary btn-sm">تحديث</button>
        </form>
    </x-ui.panel>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="إيراد (مدفوع)" :value="number_format((float) ($stats['revenue'] ?? 0), 2)" caption="ريال في الفترة" tone="teal" />
        <x-ui.metric-card label="شحن محافظ معتمد" :value="number_format((float) ($stats['topups'] ?? 0), 2)" caption="ريال" tone="blue" />
        <x-ui.metric-card label="سحوبات معتمدة" :value="number_format((float) ($stats['withdrawals'] ?? 0), 2)" caption="ريال" tone="amber" />
        <x-ui.metric-card label="طلبات مكتملة" :value="number_format($stats['completed_orders'] ?? 0)" caption="من إجمالي {{ $stats['total_orders'] ?? 0 }}" tone="slate" />
    </section>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.panel title="ملخص العملاء والشركاء">
            <ul class="space-y-2 text-sm text-slate-300">
                <li>عملاء جدد: <span class="font-bold text-white">{{ number_format($stats['new_customers'] ?? 0) }}</span></li>
                <li>بائعون جدد: <span class="font-bold text-white">{{ number_format($stats['new_sellers'] ?? 0) }}</span></li>
                <li>معدل إتمام الطلبات: <span class="font-bold text-emerald-300">{{ $stats['conversion_rate'] ?? 0 }}٪</span></li>
            </ul>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.reports.sales', request()->query()) }}" class="btn btn-outline btn-sm border-white/20 text-slate-100">تقرير المبيعات</a>
                <a href="{{ route('admin.reports.customers', request()->query()) }}" class="btn btn-outline btn-sm border-white/20 text-slate-100">أفضل العملاء</a>
            </div>
        </x-ui.panel>

        <x-ui.panel title="إيراد يومي (مدفوع)">
            <div class="max-h-64 overflow-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-right text-slate-400"><th class="py-2">اليوم</th><th class="py-2">المبلغ</th></tr></thead>
                    <tbody>
                        @forelse ($dailyRevenue as $row)
                            <tr class="border-t border-white/5 text-slate-200">
                                <td class="py-2">{{ $row->date }}</td>
                                <td class="py-2 font-mono">{{ number_format((float) $row->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-4 text-center text-slate-500">لا بيانات في الفترة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
