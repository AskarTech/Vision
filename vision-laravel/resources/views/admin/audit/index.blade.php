<x-layouts.dashboard title="سجل التدقيق المالي" description="حركات محافظ المنصة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">التدقيق</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="كل الحركات" :value="number_format($stats['total'] ?? 0)" caption="سجل" tone="slate" />
        <x-ui.metric-card label="اليوم" :value="number_format($stats['today'] ?? 0)" caption="حركة اليوم" tone="teal" />
    </section>

    <x-ui.panel>
        <form method="GET" action="{{ route('admin.audit.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
            <select name="type" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                <option value="">كل الأنواع</option>
                @foreach (['credit', 'debit', 'refund', 'adjustment', 'hold', 'release'] as $t)
                    <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <input type="number" name="user_id" value="{{ request('user_id') }}" placeholder="معرّف مستخدم" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
        </form>

        <div class="mb-4 flex flex-wrap gap-2 text-sm">
            <a href="{{ route('admin.audit.topups') }}" class="link link-hover text-emerald-300">سجل الإيداعات</a>
            <span class="text-slate-600">|</span>
            <a href="{{ route('admin.audit.withdrawals') }}" class="link link-hover text-emerald-300">سجل السحوبات</a>
            <span class="text-slate-600">|</span>
            <a href="{{ route('admin.audit.orders') }}" class="link link-hover text-emerald-300">سجل الطلبات</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm">
                <thead>
                    <tr class="text-right text-xs font-semibold text-slate-400">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">المستخدم</th>
                        <th class="px-3 py-3">النوع</th>
                        <th class="px-3 py-3">المبلغ</th>
                        <th class="px-3 py-3">النقاط</th>
                        <th class="px-3 py-3">الرصيد بعد</th>
                        <th class="px-3 py-3">التاريخ</th>
                        <th class="px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($transactions as $tx)
                        <tr class="hover:bg-white/5">
                            <td class="px-3 py-2 text-slate-400">#{{ $tx->id }}</td>
                            <td class="px-3 py-2 text-slate-200">{{ $tx->user?->name ?? '—' }}</td>
                            <td class="px-3 py-2"><x-ui.badge tone="slate">{{ $tx->type }}</x-ui.badge></td>
                            <td class="px-3 py-2 font-mono text-white">{{ number_format((float) $tx->amount, 2) }}</td>
                            <td class="px-3 py-2 text-slate-400">{{ $tx->points_amount }}</td>
                            <td class="px-3 py-2 font-mono text-emerald-300">{{ number_format((float) $tx->balance_after, 2) }}</td>
                            <td class="px-3 py-2 text-xs text-slate-500">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-3 py-2"><a href="{{ route('admin.audit.show', $tx) }}" class="btn btn-ghost btn-xs">عرض</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">لا حركات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
