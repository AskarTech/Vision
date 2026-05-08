<x-layouts.dashboard title="شبكاتي" description="إدارة شبكات البيع الخاصة بك" dashboardType="seller">
    <x-ui.page-header title="الشبكات" description="كل شبكة مرتبطة بحسابك فقط — لا تظهر لبائع آخر." />

    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    <section class="mb-6 grid gap-4 md:grid-cols-2">
        <x-ui.metric-card label="إجمالي الشبكات" :value="number_format($stats['total'] ?? 0)" caption="ضمن حسابك" tone="slate" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="جاهزة للبيع" tone="teal" class="rounded-[1.5rem]" />
    </section>

    <x-ui.panel title="قائمة الشبكات" class="rounded-[1.5rem]">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('seller.networks.create') }}" class="btn btn-primary btn-sm rounded-xl">إضافة شبكة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>المعرّف</th>
                        <th>رمز المزود</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($networks as $network)
                        <tr>
                            <td class="font-medium">{{ $network->name }}</td>
                            <td><code class="text-xs">{{ $network->slug }}</code></td>
                            <td>{{ $network->provider_code ?? '—' }}</td>
                            <td><x-ui.status-badge :status="$network->status" /></td>
                            <td class="flex flex-wrap gap-1">
                                <a href="{{ route('seller.networks.edit', $network) }}" class="btn btn-ghost btn-xs">تعديل</a>
                                <form method="POST" action="{{ route('seller.networks.destroy', $network) }}" class="inline" onsubmit="return confirm('حذف هذه الشبكة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-500">لا توجد شبكات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $networks->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
