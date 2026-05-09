<x-layouts.dashboard title="إدارة الشركاء (التجار)" description="إدارة الشركاء وحالة الحساب والعمولة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الشركاء</x-ui.badge></x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="بائع" tone="slate" />
        <x-ui.metric-card label="نشط" :value="number_format($stats['active'] ?? 0)" caption="يعمل" tone="teal" />
        <x-ui.metric-card label="معلق" :value="number_format($stats['pending'] ?? 0)" caption="انتظار" tone="amber" />
        <x-ui.metric-card label="موقوف" :value="number_format($stats['suspended'] ?? 0)" caption="معلّق" tone="rose" />
    </section>

    <x-ui.panel title="قائمة الشركاء المسجلين" description="بحث وتصفية حسب الحالة">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهاتف أو المعرف"
                    class="admin-filter-field min-w-[200px]" />
                <select name="status" class="admin-filter-field">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشط</option>
                    <option value="pending" @selected(request('status') === 'pending')>معلق</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>موقوف</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.sellers.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.sellers.create') }}" class="btn btn-sm btn-primary">إضافة شريك</a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">الشريك</th>
                        <th scope="col">الهاتف</th>
                        <th scope="col">المستخدمون</th>
                        <th scope="col">الباقات</th>
                        <th scope="col">البطاقات</th>
                        <th scope="col">العمولة %</th>
                        <th scope="col">الحالة</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sellers as $seller)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $seller->name }}</div>
                                <div class="text-xs font-normal text-slate-500">{{ $seller->slug }}</div>
                            </td>
                            <td class="font-normal text-slate-600">{{ $seller->phone ?? '—' }}</td>
                            <td class="font-normal text-slate-600">{{ $seller->users_count }}</td>
                            <td class="font-normal text-slate-600">{{ $seller->packages_count }}</td>
                            <td class="font-normal text-slate-600">{{ $seller->cards_count }}</td>
                            <td class="font-semibold text-emerald-700">{{ $seller->commission_rate }}</td>
                            <td><x-ui.badge tone="{{ $seller->status === 'active' ? 'emerald' : ($seller->status === 'pending' ? 'amber' : 'rose') }}">{{ $seller->status }}</x-ui.badge></td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="admin-outline-btn">عرض</a>
                                    <a href="{{ route('admin.sellers.edit', $seller) }}" class="admin-outline-btn">تعديل</a>
                                    @if ($seller->status === 'pending')
                                        <form method="POST" action="{{ route('admin.sellers.approve', $seller) }}" class="inline">@csrf<button type="submit" class="btn btn-success btn-xs">تفعيل</button></form>
                                    @elseif ($seller->status === 'active')
                                        <form method="POST" action="{{ route('admin.sellers.suspend', $seller) }}" class="inline" onsubmit="return confirm('تعليق هذا البائع؟');">@csrf<button type="submit" class="btn btn-warning btn-xs">تعليق</button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-500">لا شركاء مسجلين.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $sellers->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
