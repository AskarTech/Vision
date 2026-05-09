<x-layouts.dashboard title="شبكاتي" description="كل شبكة مرتبطة بحسابك فقط — لا تظهر لشريك آخر." dashboardType="seller">

    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if (session('error'))
        <x-ui.alert tone="error" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    <section class="mb-8 grid gap-6 md:grid-cols-2">
        <x-ui.metric-card label="إجمالي الشبكات" :value="number_format($stats['total'] ?? 0)" caption="ضمن حسابك" tone="slate" />
        <x-ui.metric-card label="نشطة" :value="number_format($stats['active'] ?? 0)" caption="جاهزة للبيع" tone="teal" />
    </section>

    <x-ui.panel title="قائمة الشبكات">
        <div class="mb-5 flex justify-end">
            <a href="{{ route('seller.networks.create') }}" class="btn btn-primary btn-sm">إضافة شبكة</a>
        </div>

        <div class="overflow-x-auto">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th scope="col">الاسم</th>
                        <th scope="col">المعرّف</th>
                        <th scope="col">رمز المزود</th>
                        <th scope="col">الحالة</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($networks as $network)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $network->name }}</td>
                            <td><code class="text-xs text-slate-600">{{ $network->slug }}</code></td>
                            <td class="font-normal text-slate-700">{{ $network->provider_code ?? '—' }}</td>
                            <td><x-ui.status-badge :status="$network->status" /></td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <a href="{{ route('seller.networks.edit', $network) }}" class="vision-outline-btn">تعديل</a>
                                    <form method="POST" action="{{ route('seller.networks.destroy', $network) }}" class="inline" onsubmit="return confirm('حذف هذه الشبكة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-slate-500">لا توجد شبكات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $networks->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
