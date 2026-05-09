<x-layouts.dashboard title="التقارير المالية" description="ملخص مالي وسوقي للفترة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">التقارير</x-ui.badge></x-slot>

    <x-ui.panel title="نطاق التقرير" description="اختر الفترة الزمنية">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-2 block text-sm font-bold text-slate-800">من</label>
                <input type="date" name="date_from" value="{{ \Illuminate\Support\Carbon::parse($dateFrom)->format('Y-m-d') }}" class="admin-filter-field" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-bold text-slate-800">إلى</label>
                <input type="date" name="date_to" value="{{ \Illuminate\Support\Carbon::parse($dateTo)->format('Y-m-d') }}" class="admin-filter-field" />
            </div>
            <button type="submit" class="btn btn-primary btn-sm">تحديث</button>
        </form>
    </x-ui.panel>

    <section class="admin-grid-stats mt-8">
        <x-ui.metric-card label="إيراد (مدفوع)" :value="number_format((float) ($stats['revenue'] ?? 0), 2)" caption="ريال في الفترة" tone="teal" />
        <x-ui.metric-card label="شحن محافظ معتمد" :value="number_format((float) ($stats['topups'] ?? 0), 2)" caption="ريال" tone="blue" />
        <x-ui.metric-card label="سحوبات معتمدة" :value="number_format((float) ($stats['withdrawals'] ?? 0), 2)" caption="ريال" tone="amber" />
        <x-ui.metric-card label="طلبات مكتملة" :value="number_format($stats['completed_orders'] ?? 0)" caption="من إجمالي {{ $stats['total_orders'] ?? 0 }}" tone="slate" />
    </section>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.panel title="ملخص العملاء والشركاء">
            <ul class="space-y-3 text-sm text-slate-700">
                <li>عملاء جدد: <span class="font-extrabold text-slate-900">{{ number_format($stats['new_customers'] ?? 0) }}</span></li>
                <li>بائعون جدد: <span class="font-extrabold text-slate-900">{{ number_format($stats['new_sellers'] ?? 0) }}</span></li>
                <li>معدل إتمام الطلبات: <span class="font-extrabold text-emerald-700">{{ $stats['conversion_rate'] ?? 0 }}٪</span></li>
            </ul>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.sales', request()->query()) }}" class="admin-outline-btn">تقرير المبيعات</a>
                <a href="{{ route('admin.reports.customers', request()->query()) }}" class="admin-outline-btn">أفضل العملاء</a>
            </div>
        </x-ui.panel>

        <x-ui.panel title="إيراد يومي (مدفوع)">
            <div class="max-h-64 overflow-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th scope="col">اليوم</th>
                            <th scope="col">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyRevenue as $row)
                            <tr>
                                <td class="font-normal text-slate-700">{{ $row->date }}</td>
                                <td class="font-mono font-semibold text-slate-800">{{ number_format((float) $row->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-8 text-center text-slate-500">لا بيانات في الفترة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
