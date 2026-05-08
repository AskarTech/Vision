<x-layouts.dashboard title="البائعون" description="إدارة الشركاء وحالة الحساب" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">البائعون</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="بائع" tone="slate" />
        <x-ui.metric-card label="نشط" :value="number_format($stats['active'] ?? 0)" caption="يعمل" tone="teal" />
        <x-ui.metric-card label="معلق" :value="number_format($stats['pending'] ?? 0)" caption="انتظار" tone="amber" />
        <x-ui.metric-card label="موقوف" :value="number_format($stats['suspended'] ?? 0)" caption="معلّق" tone="rose" />
    </section>

    <x-ui.panel>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهاتف أو المعرف"
                       class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 min-w-[200px]" />
                <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشط</option>
                    <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>موقوف</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.sellers.create') }}" class="btn btn-sm btn-primary">إضافة بائع</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr class="text-right text-xs font-semibold text-slate-400">
                        <th class="px-4 py-3">البائع</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">المستخدمون</th>
                        <th class="px-4 py-3">الباقات</th>
                        <th class="px-4 py-3">البطاقات</th>
                        <th class="px-4 py-3">العمولة %</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($sellers as $seller)
                        <tr class="hover:bg-white/5">
                            <td class="px-4 py-3">
                                <div class="font-medium text-white">{{ $seller->name }}</div>
                                <div class="text-xs text-slate-500">{{ $seller->slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $seller->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $seller->users_count }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $seller->packages_count }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $seller->cards_count }}</td>
                            <td class="px-4 py-3 text-sm text-emerald-300">{{ $seller->commission_rate }}</td>
                            <td class="px-4 py-3"><x-ui.badge tone="{{ $seller->status === 'active' ? 'emerald' : ($seller->status === 'pending' ? 'amber' : 'rose') }}">{{ $seller->status }}</x-ui.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="btn btn-ghost btn-xs">عرض</a>
                                    <a href="{{ route('admin.sellers.edit', $seller) }}" class="btn btn-ghost btn-xs">تعديل</a>
                                    @if ($seller->status === 'pending')
                                        <form method="POST" action="{{ route('admin.sellers.approve', $seller) }}" class="inline">@csrf<button type="submit" class="btn btn-success btn-xs">تفعيل</button></form>
                                    @elseif ($seller->status === 'active')
                                        <form method="POST" action="{{ route('admin.sellers.suspend', $seller) }}" class="inline" onsubmit="return confirm('تعليق هذا البائع؟');">@csrf<button type="submit" class="btn btn-warning btn-xs">تعليق</button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">لا بائعين.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $sellers->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
