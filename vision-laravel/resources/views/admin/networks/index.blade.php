<x-layouts.dashboard title="الشبكات" description="إدارة شبكات الشركاء" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الشبكات</x-ui.badge></x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="شبكة" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="تعمل" tone="teal" />
        <x-ui.metric-card label="معطّلة" :value="number_format($stats['inactive'] ?? 0)" caption="متوقفة" tone="rose" />
        <x-ui.metric-card label="بائعون بشبكات" :value="number_format($stats['by_seller'] ?? 0)" caption="عدد البائعين" tone="blue" />
    </section>

    <x-ui.panel title="قائمة الشبكات" description="تصفية حسب البائع والحالة">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.networks.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="admin-filter-field">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="seller_id" class="admin-filter-field">
                    <option value="">كل البائعين</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected((string) request('seller_id') === (string) $seller->id)>{{ $seller->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.networks.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.networks.create') }}" class="btn btn-sm btn-primary">إضافة شبكة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">الاسم</th>
                        <th scope="col">البائع</th>
                        <th scope="col">رمز المزود</th>
                        <th scope="col">الحالة</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($networks as $network)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $network->name }}</td>
                            <td class="font-normal text-slate-600">{{ $network->seller?->name ?? '—' }}</td>
                            <td class="text-xs font-normal text-slate-600">{{ $network->provider_code ?? '—' }}</td>
                            <td><x-ui.badge tone="{{ $network->status === 'active' ? 'emerald' : 'rose' }}">{{ $network->status }}</x-ui.badge></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.networks.show', $network) }}" class="admin-outline-btn">عرض</a>
                                    <a href="{{ route('admin.networks.edit', $network) }}" class="admin-outline-btn">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-slate-500">لا توجد شبكات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $networks->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
