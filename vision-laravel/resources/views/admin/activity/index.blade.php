<x-layouts.dashboard title="مراقبة النشاط" description="آخر حركات المحافظ على المنصة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="slate">نشاط</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-2">
        <x-ui.metric-card label="جميع الحركات" :value="number_format($stats['total'] ?? 0)" caption="في السجل" tone="slate" />
        <x-ui.metric-card label="حركات اليوم" :value="number_format($stats['today'] ?? 0)" caption="اليوم" tone="teal" />
    </section>

    <x-ui.panel>
        <p class="mb-4 text-sm text-slate-400">للتدقيق التفصيلي استخدم <a href="{{ route('admin.audit.index') }}" class="link link-hover text-emerald-300">سجل التدقيق الكامل</a>.</p>
        <form method="GET" action="{{ route('admin.activity.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
            <select name="type" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                <option value="">كل الأنواع</option>
                @foreach (['credit', 'debit', 'refund'] as $t)
                    <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.activity.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm">
                <thead>
                    <tr class="text-right text-xs font-semibold text-slate-400">
                        <th class="px-3 py-2">#</th>
                        <th class="px-3 py-2">المستخدم</th>
                        <th class="px-3 py-2">النوع</th>
                        <th class="px-3 py-2">مبلغ</th>
                        <th class="px-3 py-2">وقت</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="px-3 py-2 text-slate-500">#{{ $tx->id }}</td>
                            <td class="px-3 py-2 text-slate-200">{{ $tx->user?->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $tx->type }}</td>
                            <td class="px-3 py-2 font-mono text-emerald-200">{{ number_format((float) $tx->amount, 2) }}</td>
                            <td class="px-3 py-2 text-xs text-slate-500">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-400">لا نشاطاً بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
