<x-layouts.dashboard title="إدارة الإيداعات" description="مراجعة واعتماد طلبات التوب أب من المستخدمين" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">العمليات المالية</x-ui.badge>
    </x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="إيداعات معلقة" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج مراجعة" tone="amber" />
        <x-ui.metric-card label="إيداعات اليوم" :value="number_format($stats['today'] ?? 0)" caption="خلال آخر 24 ساعة" tone="teal" />
        <x-ui.metric-card label="إجمالي المبلغ" :value="number_format($stats['total'] ?? 0, 2)" caption="ريال يمني" tone="blue" />
        <x-ui.metric-card label="تم اعتمادها" :value="number_format($stats['approved'] ?? 0)" caption="هذا الشهر" tone="teal" />
    </section>

    <x-ui.panel title="سجل الإيداعات" description="تصفية حسب الحالة والتاريخ">
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('admin.topups.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="admin-filter-field">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />

                <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />

                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.topups.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>

            <button type="button" class="admin-outline-btn cursor-default opacity-80" disabled>📥 تصدير CSV</button>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">المستخدم</th>
                        <th scope="col">المبلغ</th>
                        <th scope="col">بوابة الدفع</th>
                        <th scope="col">المرجع</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topups as $topup)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $topup->user?->name ?? 'غير معروف' }}</div>
                                <div class="text-xs font-normal text-slate-500">{{ $topup->user?->email ?? '' }}</div>
                            </td>
                            <td>
                                <span class="font-bold text-slate-800">{{ number_format((float) $topup->amount, 2) }}</span>
                                <span class="text-xs font-normal text-slate-500">ريال</span>
                            </td>
                            <td>
                                <x-ui.badge tone="slate">{{ $topup->paymentGateway?->name ?? 'يدوي' }}</x-ui.badge>
                            </td>
                            <td>
                                <code class="text-xs font-normal text-slate-600">{{ $topup->reference_code ?? '-' }}</code>
                            </td>
                            <td>
                                <x-ui.badge tone="{{ $topup->status === 'approved' ? 'emerald' : ($topup->status === 'rejected' ? 'rose' : 'amber') }}">
                                    {{ $topup->status }}
                                </x-ui.badge>
                            </td>
                            <td class="font-normal text-slate-600">
                                {{ $topup->created_at?->format('Y-m-d H:i') ?? '-' }}
                            </td>
                            <td>
                                @if ($topup->status === 'pending')
                                    <div class="flex min-w-[140px] flex-col items-stretch gap-2">
                                        <form method="POST" action="{{ route('admin.topups.approve', $topup) }}" onsubmit="return confirm('اعتماد هذا الإيداع؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs w-full">اعتماد</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.topups.reject', $topup) }}" class="space-y-1">
                                            @csrf
                                            <input type="text" name="reason" maxlength="500" placeholder="سبب الرفض"
                                                class="input input-bordered admin-form-input-xs w-full" />
                                            <button type="submit" class="btn btn-error btn-xs w-full">رفض</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs font-normal text-slate-500">{{ $topup->reviewed_at?->format('Y-m-d H:i') ?? '—' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center font-normal text-slate-500">لا توجد إيداعات لعرضها</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $topups->links() }}
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
