<x-layouts.dashboard title="أكواد المخزون" description="مراجعة البطاقات وحالتها وتصدير CSV" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">المخزون</x-ui.badge>
    </x-slot>

    <section class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="كل البطاقات" tone="slate" />
        <x-ui.metric-card label="متاح" :value="number_format($stats['available'] ?? 0)" caption="جاهز للبيع" tone="teal" />
        <x-ui.metric-card label="محجوز" :value="number_format($stats['reserved'] ?? 0)" caption="قيد الحجز" tone="amber" />
        <x-ui.metric-card label="مباع" :value="number_format($stats['sold'] ?? 0)" caption="مكتمل" tone="blue" />
        <x-ui.metric-card label="بلاغات" :value="number_format($stats['reported'] ?? 0)" caption="يحتاج مراجعة" tone="rose" />
    </section>

    <x-ui.panel title="قائمة الأكواد" description="بحث، تصفية وتحديث جماعي">
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="admin-filter-field">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>متاح</option>
                    <option value="reserved" @selected(request('status') === 'reserved')>محجوز</option>
                    <option value="sold" @selected(request('status') === 'sold')>مباع</option>
                    <option value="reported" @selected(request('status') === 'reported')>مبلّغ</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّل</option>
                </select>
                <select name="seller_id" class="admin-filter-field">
                    <option value="">كل البائعين</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected((string) request('seller_id') === (string) $seller->id)>{{ $seller->name }}</option>
                    @endforeach
                </select>
                <select name="network_id" class="admin-filter-field">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected((string) request('network_id') === (string) $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.inventory.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.inventory.export', request()->query()) }}" class="admin-outline-btn">تصدير CSV</a>
        </div>

        <form method="POST" action="{{ route('admin.inventory.bulk-update') }}">
            @csrf
            <div class="mb-5 flex flex-wrap items-center gap-2 rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] p-4">
                <span class="text-sm font-semibold text-slate-700">تحديث جماعي للمحدد:</span>
                <select name="status" required class="admin-filter-field">
                    <option value="">— اختر الحالة —</option>
                    <option value="active">متاح</option>
                    <option value="reserved">محجوز</option>
                    <option value="sold">مباع</option>
                    <option value="reported">مبلّغ</option>
                    <option value="refunded">مسترد</option>
                    <option value="disabled">معطّل</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">تطبيق على المحدد</button>
            </div>

            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="w-10 !px-2" scope="col"></th>
                            <th scope="col">الرمز</th>
                            <th scope="col">الباقة</th>
                            <th scope="col">البائع</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">السعر</th>
                            <th scope="col">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cards as $card)
                            <tr>
                                <td class="!px-2">
                                    <input type="checkbox" name="card_ids[]" value="{{ $card->id }}" class="checkbox checkbox-sm border-[#cbd5e1]" />
                                </td>
                                <td>
                                    <code class="text-xs font-normal text-emerald-800">{{ \Illuminate\Support\Str::limit($card->code, 28) }}</code>
                                </td>
                                <td class="font-normal text-slate-600">{{ $card->package?->name ?? '—' }}</td>
                                <td class="font-normal text-slate-600">{{ $card->seller?->name ?? '—' }}</td>
                                <td>
                                    <x-ui.badge tone="{{ $card->status === 'active' ? 'emerald' : ($card->status === 'sold' ? 'blue' : ($card->status === 'reserved' ? 'amber' : 'rose')) }}">{{ $card->status }}</x-ui.badge>
                                </td>
                                <td class="font-semibold text-slate-800">{{ number_format((float) $card->price, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.inventory.show', $card) }}" class="admin-outline-btn">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-sm text-slate-500">لا توجد بطاقات مطابقة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-6">{{ $cards->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
