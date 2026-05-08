<x-layouts.dashboard title="الباقات" description="حزم الشبكات والأسعار" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الباقات</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="باقة" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="معروضة" tone="teal" />
        <x-ui.metric-card label="معطّلة" :value="number_format($stats['inactive'] ?? 0)" caption="مخفية" tone="rose" />
    </section>

    <x-ui.panel>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.packages.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="network_id" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected((string) request('network_id') === (string) $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-sm btn-primary">إضافة باقة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الاسم</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الشبكة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">البائع</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">السعر</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">النوع</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الحالة</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-white/5">
                            <td class="px-4 py-3 text-sm text-white">{{ $package->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $package->network?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $package->seller?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-emerald-300">{{ number_format((float) $package->price, 2) }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $package->period_type }}</td>
                            <td class="px-4 py-3"><x-ui.badge tone="{{ $package->status === 'active' ? 'emerald' : 'rose' }}">{{ $package->status }}</x-ui.badge></td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('admin.packages.show', $package) }}" class="btn btn-ghost btn-xs">عرض</a>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-ghost btn-xs">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">لا توجد باقات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $packages->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
