<x-layouts.dashboard title="باقاتي" description="الباقات مرئية لك وللمشرف عند الحاجة." dashboardType="seller">

    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    <section class="mb-8 grid gap-6 md:grid-cols-2">
        <x-ui.metric-card label="إجمالي الباقات" :value="number_format($stats['total'] ?? 0)" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" tone="teal" />
    </section>

    <x-ui.panel title="تصفية وإجراءات" class="mb-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <form method="GET" action="{{ route('seller.packages.index') }}" class="flex flex-wrap gap-2">
                <select name="status" class="vision-filter-field">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="network_id" class="vision-filter-field min-w-[160px]">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $n)
                        <option value="{{ $n->id }}" @selected((string) request('network_id') === (string) $n->id)>{{ $n->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('seller.packages.index') }}" class="vision-outline-btn">إعادة تعيين</a>
            </form>
            <a href="{{ route('seller.packages.create') }}" class="btn btn-primary btn-sm">إضافة باقة</a>
        </div>
    </x-ui.panel>

    <x-ui.panel title="قائمة الباقات">
        <div class="overflow-x-auto">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th scope="col">الاسم</th>
                        <th scope="col">الشبكة</th>
                        <th scope="col">السعر</th>
                        <th scope="col">البطاقات</th>
                        <th scope="col">الحالة</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $package->name }}</td>
                            <td class="font-normal text-slate-700">{{ $package->network?->name ?? '—' }}</td>
                            <td class="font-semibold text-emerald-800">{{ number_format((float) $package->price, 2) }}</td>
                            <td>{{ $package->cards_count }}</td>
                            <td><x-ui.status-badge :status="$package->status" /></td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <a href="{{ route('seller.packages.edit', $package) }}" class="vision-outline-btn">تعديل</a>
                                    <form method="POST" action="{{ route('seller.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('حذف الباقة؟ لا يُسمح إن وُجدت بطاقات.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-slate-500">لا باقات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $packages->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
