<x-layouts.dashboard title="المخزون" description="عرض وتصفية بطاقات شبكتك حسب الباقة والحالة" dashboardType="seller">

    <section class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="إجمالي النتائج" :value="number_format($stats['total'] ?? 0)" caption="في الصفحة الحالية / الكل" tone="slate" />
        <x-ui.metric-card label="متاح" :value="number_format($stats['available'] ?? 0)" caption="جاهز للبيع" tone="teal" />
        <x-ui.metric-card label="محجوز" :value="number_format($stats['reserved'] ?? 0)" caption="مؤقت" tone="amber" />
        <x-ui.metric-card label="مباع" :value="number_format($stats['sold'] ?? 0)" caption="مكتمل" tone="blue" />
    </section>

    <x-ui.panel title="تصفية المخزون" description="اختر الحالة والباقة ثم طبّق" class="mb-8">
        <form method="GET" action="{{ route('seller.inventory.index') }}" class="flex flex-wrap items-center gap-2">
            <select name="status" class="vision-filter-field min-w-[140px]">
                <option value="">كل الحالات</option>
                <option value="active" @selected(request('status') === 'active')>متاح</option>
                <option value="reserved" @selected(request('status') === 'reserved')>محجوز</option>
                <option value="sold" @selected(request('status') === 'sold')>مباع</option>
            </select>
            <select name="package_id" class="vision-filter-field min-w-[180px]">
                <option value="">كل الباقات</option>
                @foreach ($packages as $pkg)
                    <option value="{{ $pkg->id }}" @selected((string) request('package_id') === (string) $pkg->id)>{{ $pkg->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            <a href="{{ route('seller.inventory.index') }}" class="vision-outline-btn">إعادة تعيين</a>
        </form>
        <div class="mt-4">
            <a href="{{ route('seller.inventory.create') }}" class="btn btn-secondary btn-sm">إضافة / استيراد بطاقات</a>
        </div>
    </x-ui.panel>

    <x-ui.panel title="قائمة البطاقات">
        <div class="overflow-x-auto">
            <table class="vision-table">
                <thead>
                    <tr>
                        <th scope="col">الرمز</th>
                        <th scope="col">الباقة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">السعر</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cards as $card)
                        <tr>
                            <td><code class="text-xs font-normal text-emerald-900">{{ \Illuminate\Support\Str::limit($card->code, 20) }}</code></td>
                            <td class="font-normal text-slate-700">{{ $card->package?->name ?? '—' }}</td>
                            <td><x-ui.badge tone="slate">{{ $card->status }}</x-ui.badge></td>
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
                        <tr><td colspan="5" class="py-10 text-center font-normal text-slate-500">لا بطاقات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $cards->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
