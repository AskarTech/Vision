<x-layouts.dashboard title="السحوبات" description="مراجعة طلبات سحب أرباح البائعين" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="warning">السحوبات</x-ui.badge></x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج قرار" tone="amber" />
        <x-ui.metric-card label="حجم اليوم" :value="number_format((float) ($stats['today'] ?? 0), 2)" caption="ريال" tone="teal" />
        <x-ui.metric-card label="معتمدة (مجموع)" :value="number_format((float) ($stats['total'] ?? 0), 2)" caption="ريال" tone="blue" />
        <x-ui.metric-card label="عدد المعتمدة" :value="number_format($stats['approved'] ?? 0)" caption="طلب" tone="slate" />
    </section>

    <x-ui.panel title="طلبات السحب" description="اعتماد أو رفض الطلبات المعلقة">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="mb-5 flex flex-wrap items-center gap-2">
            <select name="status" class="admin-filter-field">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="approved" @selected(request('status') === 'approved')>معتمد</option>
                <option value="rejected" @selected(request('status') === 'rejected')>مرفوض</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('admin.withdrawals.index') }}" class="admin-outline-btn">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">البائع</th>
                        <th scope="col">طالب السحب</th>
                        <th scope="col">المبلغ</th>
                        <th scope="col">البنك</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td class="font-normal text-slate-600">#{{ $withdrawal->id }}</td>
                            <td class="font-semibold text-slate-800">{{ $withdrawal->seller?->name ?? '—' }}</td>
                            <td class="font-normal text-slate-600">{{ $withdrawal->requester?->name ?? '—' }}</td>
                            <td class="font-bold text-emerald-700">{{ number_format((float) $withdrawal->amount, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $withdrawal->bank_name ?? '—' }} / {{ $withdrawal->account_number ?? '—' }}</td>
                            <td>
                                <x-ui.badge tone="{{ $withdrawal->status === 'approved' ? 'emerald' : ($withdrawal->status === 'rejected' ? 'rose' : 'amber') }}">{{ $withdrawal->status }}</x-ui.badge>
                            </td>
                            <td class="text-xs font-normal text-slate-600">{{ $withdrawal->created_at?->format('Y-m-d H:i') }}</td>
                            <td>
                                @if ($withdrawal->status === 'pending')
                                    <div class="flex flex-col gap-2">
                                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" onsubmit="return confirm('اعتماد هذا السحب؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs w-full">اعتماد</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="space-y-1">
                                            @csrf
                                            <input type="text" name="reason" maxlength="500" placeholder="سبب الرفض (اختياري)"
                                                class="input input-bordered admin-form-input-xs w-full max-w-[180px]" />
                                            <button type="submit" class="btn btn-error btn-xs w-full">رفض</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-500">لا طلبات سحب.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
