<x-layouts.dashboard title="مراقبة النشاط" description="آخر حركات المحافظ على المنصة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="slate">نشاط</x-ui.badge></x-slot>

    <section class="mb-8 grid gap-6 md:grid-cols-2">
        <x-ui.metric-card label="جميع الحركات" :value="number_format($stats['total'] ?? 0)" caption="في السجل" tone="slate" />
        <x-ui.metric-card label="حركات اليوم" :value="number_format($stats['today'] ?? 0)" caption="اليوم" tone="teal" />
    </section>

    <x-ui.panel title="آخر النشاط" description="نظرة سريعة — للتفاصيل استخدم الجرد والمحاسبة">
        <p class="mb-4 text-sm text-slate-600">للتدقيق التفصيلي استخدم <a href="{{ route('admin.audit.index') }}" class="font-semibold text-[var(--vision-teal-dark)] hover:underline">الجرد والمحاسبة</a>.</p>
        <form method="GET" action="{{ route('admin.activity.index') }}" class="mb-5 flex flex-wrap items-center gap-2">
            <select name="type" class="admin-filter-field">
                <option value="">كل الأنواع</option>
                @foreach (['credit', 'debit', 'refund'] as $t)
                    <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.activity.index') }}" class="admin-outline-btn">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">المستخدم</th>
                        <th scope="col">النوع</th>
                        <th scope="col">مبلغ</th>
                        <th scope="col">وقت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="font-normal text-slate-600">#{{ $tx->id }}</td>
                            <td class="font-normal text-slate-800">{{ $tx->user?->name ?? '—' }}</td>
                            <td class="font-normal text-slate-700">{{ $tx->type }}</td>
                            <td class="font-mono font-semibold text-emerald-700">{{ number_format((float) $tx->amount, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-slate-500">لا نشاطاً بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
