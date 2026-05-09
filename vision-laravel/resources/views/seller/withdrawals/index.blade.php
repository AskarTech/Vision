<x-layouts.dashboard title="سحوباتي" description="متابعة حالة الطلبات إلى حسابك البنكي." dashboardType="seller">

    <section class="mb-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="طلب" tone="amber" />
        <x-ui.metric-card label="مبلغ معلّق" :value="number_format((float) ($stats['pending_amount'] ?? 0), 2)" caption="ريال" tone="rose" />
        <x-ui.metric-card label="معتمدة" :value="number_format($stats['approved'] ?? 0)" caption="طلب" tone="teal" />
        <x-ui.metric-card label="مجموع المعتمد" :value="number_format((float) ($stats['total_approved'] ?? 0), 2)" caption="ريال" tone="blue" />
    </section>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary btn-sm">طلب سحب جديد</a>
    </div>

    <x-ui.panel title="طلبات السحب">
        <form method="GET" action="{{ route('seller.withdrawals.index') }}" class="mb-6 flex flex-wrap gap-2">
            <select name="status" class="vision-filter-field">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="approved" @selected(request('status') === 'approved')>معتمد</option>
                <option value="rejected" @selected(request('status') === 'rejected')>مرفوض</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="vision-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="vision-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('seller.withdrawals.index') }}" class="vision-outline-btn">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">المبلغ</th>
                        <th scope="col">البنك</th>
                        <th scope="col">المستلم</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $w)
                        <tr>
                            <td class="text-slate-600">#{{ $w->id }}</td>
                            <td class="font-semibold text-emerald-800">{{ number_format((float) $w->amount, 2) }}</td>
                            <td class="font-normal text-slate-700">{{ $w->bank_name ?? '—' }}</td>
                            <td class="font-normal text-slate-700">{{ $w->receiver_name ?? '—' }}</td>
                            <td><x-ui.badge tone="slate">{{ $w->status }}</x-ui.badge></td>
                            <td class="text-xs font-normal text-slate-600">{{ $w->created_at?->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('seller.withdrawals.show', $w) }}" class="vision-outline-btn">عرض</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-slate-500">لا طلبات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
