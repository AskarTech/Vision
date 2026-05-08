<x-layouts.customer title="{{ $package->name }}" description="تفاصيل الباقة وخيارات الشراء السريع.">
    <div class="grid gap-6 lg:grid-cols-3">
        <x-ui.panel title="{{ $package->name }}" class="lg:col-span-2">
            <p class="text-sm text-slate-300">الشبكة: {{ $package->network?->name }}</p>
            <p class="mt-2 text-sm text-slate-300">الفئة: {{ $package->category }}</p>
            <p class="mt-2 text-sm text-slate-300">الدورية: {{ $package->period_type }}</p>
            <p class="mt-4 text-xl font-bold text-emerald-300">{{ number_format((float)$package->price, 2) }}</p>

            <form method="POST" action="{{ route('customer.marketplace.buy', $package) }}" class="mt-5 flex flex-wrap items-center gap-2">
                @csrf
                <button type="submit" class="btn btn-primary">شراء بالمحفظة</button>
                <a href="{{ route('customer.wallet.index') }}" class="btn btn-ghost">شحن المحفظة</a>
            </form>
        </x-ui.panel>

        <x-ui.panel title="باقات مشابهة">
            <div class="space-y-2">
                @forelse($relatedPackages as $related)
                    <a href="{{ route('customer.marketplace.show', $related) }}" class="block rounded-xl border border-white/10 px-3 py-2 hover:bg-white/5">
                        <p class="text-sm text-white">{{ $related->name }}</p>
                        <p class="text-xs text-slate-400">{{ number_format((float)$related->price, 2) }}</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">لا توجد باقات مشابهة.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-layouts.customer>
