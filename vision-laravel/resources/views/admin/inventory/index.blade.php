<x-layouts.dashboard title="المخزون" description="مراجعة البطاقات وحالتها وتصدير CSV" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">المخزون</x-ui.badge>
    </x-slot>

    <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="الإجمالي" :value="number_format($stats['total'] ?? 0)" caption="كل البطاقات" tone="slate" />
        <x-ui.metric-card label="متاح" :value="number_format($stats['available'] ?? 0)" caption="جاهز للبيع" tone="teal" />
        <x-ui.metric-card label="محجوز" :value="number_format($stats['reserved'] ?? 0)" caption="قيد الحجز" tone="amber" />
        <x-ui.metric-card label="مباع" :value="number_format($stats['sold'] ?? 0)" caption="مكتمل" tone="blue" />
        <x-ui.metric-card label="بلاغات" :value="number_format($stats['reported'] ?? 0)" caption="يحتاج مراجعة" tone="rose" />
    </section>

    <x-ui.panel>
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(request('status') === 'active')>متاح</option>
                    <option value="reserved" @selected(request('status') === 'reserved')>محجوز</option>
                    <option value="sold" @selected(request('status') === 'sold')>مباع</option>
                    <option value="reported" @selected(request('status') === 'reported')>مبلّغ</option>
                    <option value="disabled" @selected(request('status') === 'disabled')>معطّل</option>
                </select>
                <select name="seller_id" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">كل البائعين</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected((string) request('seller_id') === (string) $seller->id)>{{ $seller->name }}</option>
                    @endforeach
                </select>
                <select name="network_id" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">كل الشبكات</option>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected((string) request('network_id') === (string) $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
            </form>
            <a href="{{ route('admin.inventory.export', request()->query()) }}" class="btn btn-outline btn-sm border-white/20 text-slate-100">تصدير CSV</a>
        </div>

        <form method="POST" action="{{ route('admin.inventory.bulk-update') }}">
            @csrf
            <div class="mb-4 flex flex-wrap items-center gap-2 rounded-xl border border-white/10 bg-white/5 p-3">
                <span class="text-sm text-slate-400">تحديث جماعي للمحدد:</span>
                <select name="status" required class="rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm text-slate-100">
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
                <table class="min-w-full divide-y divide-white/10">
                    <thead>
                        <tr>
                            <th class="w-10 px-2 py-3"></th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الرمز</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الباقة</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">البائع</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">السعر</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($cards as $card)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-2 py-3">
                                    <input type="checkbox" name="card_ids[]" value="{{ $card->id }}" class="checkbox checkbox-sm border-white/30" />
                                </td>
                                <td class="px-4 py-3">
                                    <code class="text-xs text-emerald-300">{{ \Illuminate\Support\Str::limit($card->code, 28) }}</code>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $card->package?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $card->seller?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-ui.badge tone="{{ $card->status === 'active' ? 'emerald' : ($card->status === 'sold' ? 'blue' : ($card->status === 'reserved' ? 'amber' : 'rose')) }}">{{ $card->status }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-white">{{ number_format((float) $card->price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.inventory.show', $card) }}" class="btn btn-ghost btn-xs text-slate-300">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-400">لا توجد بطاقات مطابقة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-6">{{ $cards->links() }}</div>
    </x-ui.panel>
</x-layouts.dashboard>
