<x-layouts.dashboard title="مخزوني" description="بطاقاتك حسب الباقة والحالة" dashboardType="seller">
    <x-ui.page-header title="المخزون" description="عرض وتصفية بطاقات شبكتك." />

    <div class="mb-6 grid gap-4 md:grid-cols-4">
        <x-ui.metric-card label="إجمالي النتائج" :value="number_format($stats['total'] ?? 0)" caption="في الصفحة الحالية / الكل" tone="slate" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="متاح" :value="number_format($stats['available'] ?? 0)" caption="جاهز للبيع" tone="teal" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="محجوز" :value="number_format($stats['reserved'] ?? 0)" caption="مؤقت" tone="amber" class="rounded-[1.5rem]" />
        <x-ui.metric-card label="مباع" :value="number_format($stats['sold'] ?? 0)" caption="مكتمل" tone="blue" class="rounded-[1.5rem]" />
    </div>

    <x-ui.panel title="الفلترة" class="mb-6 rounded-[1.5rem]">
        <form method="GET" action="{{ route('seller.inventory.index') }}" class="flex flex-wrap items-center gap-2">
            <select name="status" class="select select-bordered select-sm rounded-xl border-slate-200">
                <option value="">كل الحالات</option>
                <option value="active" @selected(request('status') === 'active')>متاح</option>
                <option value="reserved" @selected(request('status') === 'reserved')>محجوز</option>
                <option value="sold" @selected(request('status') === 'sold')>مباع</option>
            </select>
            <select name="package_id" class="select select-bordered select-sm rounded-xl border-slate-200">
                <option value="">كل الباقات</option>
                @foreach ($packages as $pkg)
                    <option value="{{ $pkg->id }}" @selected((string) request('package_id') === (string) $pkg->id)>{{ $pkg->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm rounded-xl">تصفية</button>
            <a href="{{ route('seller.inventory.index') }}" class="btn btn-ghost btn-sm rounded-xl">إعادة تعيين</a>
        </form>
        <a href="{{ route('seller.inventory.create') }}" class="btn btn-secondary btn-sm mt-4 rounded-xl">إضافة / استيراد بطاقات</a>
    </x-ui.panel>

    <x-ui.panel title="البطاقات" class="rounded-[1.5rem]">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>الرمز</th>
                        <th>الباقة</th>
                        <th>الحالة</th>
                        <th>السعر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cards as $card)
                        <tr>
                            <td><code class="text-xs">{{ \Illuminate\Support\Str::limit($card->code, 20) }}</code></td>
                            <td>{{ $card->package?->name ?? '—' }}</td>
                            <td><span class="badge badge-sm">{{ $card->status }}</span></td>
                            <td>{{ number_format((float) $card->price, 2) }}</td>
                            <td>
                                @if ($card->status !== 'sold')
                                    <form method="POST" action="{{ route('seller.inventory.destroy', $card) }}" class="inline" onsubmit="return confirm('حذف هذه البطاقة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error">حذف</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate-500 py-8">لا بطاقات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $cards->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
