<x-layouts.customer title="المتجر" description="استعرض باقات الإنترنت المتاحة حسب الشبكة والفئة.">
    <x-ui.panel>
        <x-ui.filter-bar :action="route('customer.marketplace.index')">
            <input name="search" value="{{ request('search') }}" class="input input-bordered input-sm w-full" placeholder="ابحث عن باقة أو شبكة" />
            <select name="network_id" class="select select-bordered select-sm w-full">
                <option value="">كل الشبكات</option>
                @foreach($networks as $network)
                    <option value="{{ $network->id }}" @selected((int)request('network_id') === $network->id)>{{ $network->name }}</option>
                @endforeach
            </select>
            <select name="category" class="select select-bordered select-sm w-full">
                <option value="">كل الفئات</option>
                @foreach(['best_selling', 'daily', 'weekly', 'monthly'] as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </x-ui.filter-bar>
    </x-ui.panel>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($packages as $package)
            <a href="{{ route('customer.marketplace.show', $package) }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:-translate-y-0.5 hover:bg-white/10">
                <p class="text-sm text-slate-400">{{ $package->network?->name }}</p>
                <h3 class="mt-1 font-semibold text-white">{{ $package->name }}</h3>
                <p class="mt-1 text-xs text-slate-400">{{ $package->category }} - {{ $package->period_type }}</p>
                <p class="mt-4 text-lg font-bold text-emerald-300">{{ number_format((float)$package->price, 2) }}</p>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-white/20 p-8 text-center text-slate-400">لا توجد باقات مطابقة للبحث.</div>
        @endforelse
    </section>

    <div class="mt-6">{{ $packages->links() }}</div>
</x-layouts.customer>
