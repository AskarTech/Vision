<x-layouts.dashboard title="السحوبات" description="طلبات تحويل أرباحك" dashboardType="seller">
    <x-ui.page-header title="سحوباتي" description="متابعة حالة الطلبات إلى حسابك البنكي." />

    <div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <x-ui.metric-card label="معلقة" :value="number_format($stats['pending'] ?? 0)" caption="طلب" tone="amber" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="مبلغ معلّق" :value="number_format((float) ($stats['pending_amount'] ?? 0), 2)" caption="ريال" tone="rose" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="معتمدة" :value="number_format($stats['approved'] ?? 0)" caption="طلب" tone="teal" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="مجموع المعتمد" :value="number_format((float) ($stats['total_approved'] ?? 0), 2)" caption="ريال" tone="blue" class="rounded-[1.5rem]" />
    </div>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary btn-sm rounded-xl">طلب سحب جديد</a>
    </div>

    <x-ui.panel title="الطلبات" class="rounded-[1.5rem]">
        <form method="GET" action="{{ route('seller.withdrawals.index') }}" class="mb-4 flex flex-wrap gap-2">
            <select name="status" class="select select-bordered select-sm rounded-xl">
                <option value="">كل الحالات</option>
                <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                <option value="approved" @selected(request('status') === 'approved')>معتمد</option>
                <option value="rejected" @selected(request('status') === 'rejected')>مرفوض</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input input-bordered input-sm rounded-xl" />
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input input-bordered input-sm rounded-xl" />
            <button type="submit" class="btn btn-primary btn-sm rounded-xl">تصفية</button>
            <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-ghost btn-sm rounded-xl">إعادة تعيين</a>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المبلغ</th>
                        <th>البنك</th>
                        <th>المستلم</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $w)
                        <tr>
                            <td>#{{ $w->id }}</td>
                            <td class="font-semibold">{{ number_format((float) $w->amount, 2) }}</td>
                            <td>{{ $w->bank_name ?? '—' }}</td>
                            <td>{{ $w->receiver_name ?? '—' }}</td>
                            <td><span class="badge badge-sm">{{ $w->status }}</span></td>
                            <td class="text-xs text-slate-500">{{ $w->created_at?->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('seller.withdrawals.show', $w) }}" class="btn btn-ghost btn-xs">عرض</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-8 text-slate-500">لا طلبات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $withdrawals->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
