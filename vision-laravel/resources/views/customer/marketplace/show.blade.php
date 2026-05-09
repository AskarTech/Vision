@php
    $stockCount = (int) ($package->active_stock_count ?? 0);
@endphp

<x-layouts.customer title="{{ $package->name }}" description="تفاصيل الباقة وخيارات الشراء السريع.">
    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="{{ $package->name }}" class="lg:col-span-2">
            <p class="text-sm text-slate-300">الشبكة: {{ $package->network?->name }}</p>
            <p class="mt-2 text-sm text-slate-300">الفئة: {{ $package->category }}</p>
            <p class="mt-2 text-sm text-slate-300">الدورية: {{ $package->period_type }}</p>
            <p class="mt-3 text-sm text-slate-400">
                المتاح للبيع الآن:
                @if ($stockCount === 0)
                    <span class="font-semibold text-rose-300">غير متوفر (0)</span>
                @else
                    <span class="font-semibold text-emerald-300">{{ $stockCount }} بطاقة</span>
                @endif
            </p>
            <p class="mt-4 text-xl font-bold text-emerald-300">{{ number_format((float)$package->price, 2) }}</p>

            @if ($inStock)
                <form method="POST" action="{{ route('customer.marketplace.buy', $package) }}"
                    class="mt-5 flex flex-wrap items-center gap-2"
                    x-data="{ submitting: false }"
                    @submit="submitting = true">
                    @csrf
                    <button type="submit" class="btn btn-primary min-h-[44px] min-w-[10rem]"
                        :disabled="submitting"
                        :class="submitting && 'btn-disabled pointer-events-none opacity-80'">
                        <span x-show="!submitting">شراء بالمحفظة</span>
                        <span x-show="submitting" x-cloak class="loading loading-spinner loading-sm"></span>
                        <span x-show="submitting" x-cloak class="mr-2">جاري التنفيذ…</span>
                    </button>
                    <a href="{{ route('customer.wallet.index') }}" class="btn btn-ghost min-h-[44px]">شحن المحفظة</a>
                </form>
            @else
                <div class="mt-5 rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                    لا يمكن الشراء حالياً: نفد مخزون هذه الباقة. جرّب باقة مشابهة أو عد لاحقاً.
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('customer.marketplace.index') }}" class="btn btn-outline btn-sm min-h-[44px]">المتجر</a>
                    <a href="{{ route('customer.wallet.index') }}" class="btn btn-ghost btn-sm min-h-[44px]">المحفظة</a>
                </div>
            @endif
        </x-ui.panel>

        <x-ui.panel title="باقات مشابهة">
            <div class="space-y-2">
                @forelse($relatedPackages as $related)
                    @php $rs = (int) ($related->active_stock_count ?? 0); @endphp
                    <a href="{{ route('customer.marketplace.show', $related) }}" class="block rounded-xl border border-white/10 px-3 py-2 hover:bg-white/5">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm text-white">{{ $related->name }}</p>
                            @if ($rs === 0)
                                <span class="shrink-0 text-[10px] text-rose-300">نفد</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400">{{ number_format((float)$related->price, 2) }}</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">لا توجد باقات مشابهة.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.customer>
