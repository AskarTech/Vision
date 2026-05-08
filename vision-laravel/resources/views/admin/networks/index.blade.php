<x-layouts.dashboard title="الشبكات" description="إدارة شبكات البائعين" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الشبكات</x-ui.badge></x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="شبكة" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="تعمل" tone="teal" />
        <x-ui.metric-card label="معطّلة" :value="number_format($stats['inactive'] ?? 0)" caption="متوقفة" tone="rose" />
        <x-ui.metric-card label="بائعون بشبكات" :value="number_format($stats['by_seller'] ?? 0)" caption="عدد البائعين" tone="blue" />
    </section>

    <x-ui.panel>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.networks.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="seller_id" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                    <option value="">كل البائعين</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected((string) request('seller_id') === (string) $seller->id)>{{ $seller->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.networks.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.networks.create') }}" class="btn btn-sm btn-primary">إضافة شبكة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الاسم</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">البائع</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">رمز المزود</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-400">الحالة</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($networks as $network)
                        <tr class="hover:bg-white/5">
                            <td class="px-4 py-3 text-sm text-white">{{ $network->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $network->seller?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $network->provider_code ?? '—' }}</td>
                            <td class="px-4 py-3"><x-ui.badge tone="{{ $network->status === 'active' ? 'emerald' : 'rose' }}">{{ $network->status }}</x-ui.badge></td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('admin.networks.show', $network) }}" class="btn btn-ghost btn-xs">عرض</a>
                                <a href="{{ route('admin.networks.edit', $network) }}" class="btn btn-ghost btn-xs">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">لا توجد شبكات.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $networks->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
