<x-layouts.dashboard title="إدارة الإيداعات" description="مراجعة واعتماد طلبات التوب أب من المستخدمين" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">العمليات المالية</x-ui.badge>
    </x-slot>

    {{-- Metrics Summary --}}
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-6">
        <x-ui.metric-card label="إيداعات معلقة" :value="number_format($stats['pending'] ?? 0)" caption="تحتاج مراجعة" tone="amber" />
        <x-ui.metric-card label="إيداعات اليوم" :value="number_format($stats['today'] ?? 0)" caption="خلال آخر 24 ساعة" tone="teal" />
        <x-ui.metric-card label="إجمالي المبلغ" :value="number_format($stats['total'] ?? 0, 2)" caption="ريال يمني" tone="blue" />
        <x-ui.metric-card label="تم اعتمادها" :value="number_format($stats['approved'] ?? 0)" caption="هذا الشهر" tone="emerald" />
    </section>

    {{-- Filters and Actions --}}
    <x-ui.panel>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('admin.topups.index') }}" class="flex items-center gap-2">
                    <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                    
                    <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="من تاريخ" 
                           class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400" />
                    
                    <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="إلى تاريخ" 
                           class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400" />
                    
                    <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                    <a href="{{ route('admin.topups.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
                </form>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="btn btn-ghost btn-sm text-slate-300">
                    📥 تصدير CSV
                </button>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">المستخدم</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">المبلغ</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">بوابة الدفع</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">المرجع</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">التاريخ</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topups as $topup)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td>
                            <div class="font-medium text-white">{{ $topup->user?->name ?? 'غير معروف' }}</div>
                            <div class="text-xs text-slate-400">{{ $topup->user?->email ?? '' }}</div>
                        </td>
                        <td>
                            <span class="font-bold text-white">{{ number_format((float)$topup->amount, 2) }}</span>
                            <span class="text-xs text-slate-400">ريال</span>
                        </td>
                        <td>
                            <x-ui.badge tone="slate">{{ $topup->paymentGateway?->name ?? 'يدوي' }}</x-ui.badge>
                        </td>
                        <td>
                            <code class="text-xs text-slate-300">{{ $topup->reference_code ?? '-' }}</code>
                        </td>
                        <td>
                            <x-ui.badge tone="{{ $topup->status === 'approved' ? 'emerald' : ($topup->status === 'rejected' ? 'rose' : 'amber') }}">
                                {{ $topup->status }}
                            </x-ui.badge>
                        </td>
                        <td class="text-sm text-slate-400">
                            {{ $topup->created_at?->format('Y-m-d H:i') ?? '-' }}
                        </td>
                        <td>
                            @if ($topup->status === 'pending')
                                <div class="flex flex-col gap-2 items-stretch min-w-[140px]">
                                    <form method="POST" action="{{ route('admin.topups.approve', $topup) }}" onsubmit="return confirm('اعتماد هذا الإيداع؟');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-xs w-full">اعتماد</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.topups.reject', $topup) }}" class="space-y-1">
                                        @csrf
                                        <input type="text" name="reason" maxlength="500" placeholder="سبب الرفض" class="input input-bordered input-xs w-full border-white/10 bg-slate-900 text-slate-100" />
                                        <button type="submit" class="btn btn-error btn-xs w-full">رفض</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-slate-500">{{ $topup->reviewed_at?->format('Y-m-d H:i') ?? '—' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-400">لا توجد إيداعات لعرضها</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $topups->links() }}
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
