<x-layouts.dashboard title="إدارة الباقات" description="حزم الشبكات والأسعار" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الباقات</x-ui.badge></x-slot>

    <section class="mb-8 grid gap-6 md:grid-cols-3">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="باقة" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="معروضة" tone="teal" />
        <x-ui.metric-card label="معطّلة" :value="number_format($stats['inactive'] ?? 0)" caption="مخفية" tone="rose" />
    </section>

    <x-ui.panel title="قائمة الباقات" description="تصفية حسب الشبكة والحالة">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.packages.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="admin-filter-field">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="network_id" class="admin-filter-field">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected((string) request('network_id') === (string) $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.packages.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-sm btn-primary">إضافة باقة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">الاسم</th>
                        <th scope="col">الشبكة</th>
                        <th scope="col">البائع</th>
                        <th scope="col">السعر</th>
                        <th scope="col">النوع</th>
                        <th scope="col">الحالة</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $package->name }}</td>
                            <td class="font-normal text-slate-600">{{ $package->network?->name ?? '—' }}</td>
                            <td class="font-normal text-slate-600">{{ $package->seller?->name ?? '—' }}</td>
                            <td class="font-semibold text-emerald-700">{{ number_format((float) $package->price, 2) }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $package->period_type }}</td>
                            <td><x-ui.badge tone="{{ $package->status === 'active' ? 'emerald' : 'rose' }}">{{ $package->status }}</x-ui.badge></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.packages.show', $package) }}" class="admin-outline-btn">عرض</a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="admin-outline-btn">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-slate-500">لا توجد باقات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $packages->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
