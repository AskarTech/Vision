<x-layouts.dashboard title="الجرد والمحاسبة" description="حركات محافظ المنصة وسجلات التدقيق" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">التدقيق</x-ui.badge></x-slot>

    <section class="mb-8 grid gap-6 md:grid-cols-2">
        <x-ui.metric-card label="كل الحركات" :value="number_format($stats['total'] ?? 0)" caption="سجل" tone="slate" />
        <x-ui.metric-card label="اليوم" :value="number_format($stats['today'] ?? 0)" caption="حركة اليوم" tone="teal" />
    </section>

    <x-ui.panel title="سجل الحركات المالية" description="تصفية حسب النوع والمستخدم والفترة">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="mb-5 flex flex-wrap items-center gap-2">
            <select name="type" class="admin-filter-field">
                <option value="">كل الأنواع</option>
                @foreach (['credit', 'debit', 'refund', 'adjustment', 'hold', 'release'] as $t)
                    <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <input type="number" name="user_id" value="{{ request('user_id') }}" placeholder="معرّف مستخدم" class="admin-filter-field min-w-[140px]" />
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.audit.index') }}" class="admin-outline-btn">إعادة تعيين</a>
        </form>

        <div class="mb-5 flex flex-wrap gap-3 text-sm">
            <a href="{{ route('admin.audit.topups') }}" class="font-semibold text-[var(--vision-teal-dark)] hover:underline">سجل الإيداعات</a>
            <span class="text-slate-400">|</span>
            <a href="{{ route('admin.audit.withdrawals') }}" class="font-semibold text-[var(--vision-teal-dark)] hover:underline">سجل السحوبات</a>
            <span class="text-slate-400">|</span>
            <a href="{{ route('admin.audit.orders') }}" class="font-semibold text-[var(--vision-teal-dark)] hover:underline">سجل الطلبات</a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">المستخدم</th>
                        <th scope="col">النوع</th>
                        <th scope="col">المبلغ</th>
                        <th scope="col">النقاط</th>
                        <th scope="col">الرصيد بعد</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="font-normal text-slate-600">#{{ $tx->id }}</td>
                            <td class="font-normal text-slate-800">{{ $tx->user?->name ?? '—' }}</td>
                            <td><x-ui.badge tone="slate">{{ $tx->type }}</x-ui.badge></td>
                            <td class="font-mono font-semibold text-slate-800">{{ number_format((float) $tx->amount, 2) }}</td>
                            <td class="font-normal text-slate-600">{{ $tx->points_amount }}</td>
                            <td class="font-mono font-semibold text-emerald-700">{{ number_format((float) $tx->balance_after, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('admin.audit.show', $tx) }}" class="admin-outline-btn">عرض</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-500">لا حركات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
