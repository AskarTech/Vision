<x-layouts.dashboard title="السحوبات" description="مراجعة طلبات سحب أرباح البائعين" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="warning">السحوبات</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج قرار" tone="amber" />
        <x-ui.metric-card label="حجم اليوم" :value="number_format((float) ($stats['today'] ?? 0), 2)" caption="ريال" tone="teal" />
        <x-ui.metric-card label="معتمدة (مجموع)" :value="number_format((float) ($stats['total'] ?? 0), 2)" caption="ريال" tone="blue" />
        <x-ui.metric-card label="عدد المعتمدة" :value="number_format($stats['approved'] ?? 0)" caption="طلب" tone="slate" />
    </section>

    <x-ui.panel>
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
            <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="approved" @selected(request('status') === 'approved')>معتمد</option>
                <option value="rejected" @selected(request('status') === 'rejected')>مرفوض</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-sm">
                <thead>
                    <tr class="text-right text-xs font-semibold text-slate-400">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">البائع</th>
                        <th class="px-3 py-3">طالب السحب</th>
                        <th class="px-3 py-3">المبلغ</th>
                        <th class="px-3 py-3">البنك</th>
                        <th class="px-3 py-3">الحالة</th>
                        <th class="px-3 py-3">التاريخ</th>
                        <th class="px-3 py-3">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($withdrawals as $withdrawal)
                        <tr class="hover:bg-white/5">
                            <td class="px-3 py-3 text-slate-400">#{{ $withdrawal->id }}</td>
                            <td class="px-3 py-3 font-medium text-white">{{ $withdrawal->seller?->name ?? '—' }}</td>
                            <td class="px-3 py-3 text-slate-300">{{ $withdrawal->requester?->name ?? '—' }}</td>
                            <td class="px-3 py-3 font-bold text-emerald-300">{{ number_format((float) $withdrawal->amount, 2) }}</td>
                            <td class="px-3 py-3 text-slate-400">{{ $withdrawal->bank_name ?? '—' }} / {{ $withdrawal->account_number ?? '—' }}</td>
                            <td class="px-3 py-3">
                                <x-ui.badge tone="{{ $withdrawal->status === 'approved' ? 'emerald' : ($withdrawal->status === 'rejected' ? 'rose' : 'amber') }}">{{ $withdrawal->status }}</x-ui.badge>
                            </td>
                            <td class="px-3 py-3 text-xs text-slate-500">{{ $withdrawal->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-3 py-3">
                                @if ($withdrawal->status === 'pending')
                                    <div class="flex flex-col gap-2">
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" onsubmit="return confirm('اعتماد هذا السحب؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs w-full">اعتماد</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="space-y-1">
                                            @csrf
                                            <input type="text" name="reason" maxlength="500" placeholder="سبب الرفض (اختياري)" class="input input-bordered input-xs w-full max-w-[180px] border-white/10 bg-slate-900 text-slate-100" />
                                            <button type="submit" class="btn btn-error btn-xs w-full">رفض</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">لا طلبات سحب.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
