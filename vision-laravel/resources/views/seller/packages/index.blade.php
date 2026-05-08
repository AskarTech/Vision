<x-layouts.dashboard title="باقاتي" description="حزم الأسعار والشبكات ضمن حسابك" dashboardType="seller">
    <x-ui.page-header title="الباقات" description="الباقات مرئية لك أنت فقط وللمشرف عند الحاجة." />

    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    <section class="mb-6 grid gap-4 md:grid-cols-2">
        <x-ui.metric-card label="إجمالي الباقات" :value="number_format($stats['total'] ?? 0)" tone="slate" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" tone="teal" class="rounded-[1.5rem]" />
    </section>

    <x-ui.panel title="الفلترة والإجراءات" class="mb-6 rounded-[1.5rem]">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <form method="GET" action="{{ route('seller.packages.index') }}" class="flex flex-wrap gap-2">
                <select name="status" class="select select-bordered select-sm rounded-xl">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>نشطة</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّلة</option>
                </select>
                <select name="network_id" class="select select-bordered select-sm rounded-xl">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $n)
                        <option value="{{ $n->id }}" @selected((string) request('network_id') === (string) $n->id)>{{ $n->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm rounded-xl">تصفية</button>
                <a href="{{ route('seller.packages.index') }}" class="btn btn-ghost btn-sm rounded-xl">إعادة تعيين</a>
            </form>
            <a href="{{ route('seller.packages.create') }}" class="btn btn-primary btn-sm rounded-xl">إضافة باقة</a>
        </div>
    </x-ui.panel>

    <x-ui.panel title="قائمة الباقات" class="rounded-[1.5rem]">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الشبكة</th>
                        <th>السعر</th>
                        <th>البطاقات</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td class="font-medium">{{ $package->name }}</td>
                            <td>{{ $package->network?->name ?? '—' }}</td>
                            <td>{{ number_format((float) $package->price, 2) }}</td>
                            <td>{{ $package->cards_count }}</td>
                            <td><x-ui.status-badge :status="$package->status" /></td>
                            <td class="flex flex-wrap gap-1">
                                <a href="{{ route('seller.packages.edit', $package) }}" class="btn btn-ghost btn-xs">تعديل</a>
                                <form method="POST" action="{{ route('seller.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('حذف الباقة؟ لا يُسمح إن وُجدت بطاقات.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-slate-500">لا باقات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $packages->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
