<x-layouts.dashboard title="باقة جديدة" dashboardType="seller">
    <x-ui.page-header title="إضافة باقة" description="اختر شبكة نشطة من شبكاتك." />

    <x-ui.panel class="rounded-[1.5rem]">
        <form method="POST" action="{{ route('seller.packages.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text">الشبكة</span>
                <select name="network_id" class="select select-bordered rounded-xl" required>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected(old('network_id') == $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">اسم الباقة</span>
                <input name="name" value="{{ old('name') }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control">
                <span class="label-text">السعر (ريال)</span>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control">
                <span class="label-text">الكمية (اختياري)</span>
                <input type="number" name="amount" value="{{ old('amount') }}" class="input input-bordered rounded-xl" />
            </label>
            <label class="form-control">
                <span class="label-text">الوحدة</span>
                <input name="unit" value="{{ old('unit') }}" class="input input-bordered rounded-xl" placeholder="GB / يوم ..." />
            </label>
            <label class="form-control">
                <span class="label-text">الدورية</span>
                <select name="period_type" class="select select-bordered rounded-xl">
                    @foreach (['daily', 'weekly', 'monthly'] as $p)
                        <option value="{{ $p }}" @selected(old('period_type', 'monthly') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">الفئة</span>
                <input name="category" value="{{ old('category', 'monthly') }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">Gradient (اختياري)</span>
                <input name="gradient" value="{{ old('gradient') }}" class="input input-bordered rounded-xl" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">الحالة</span>
                <select name="status" class="select select-bordered rounded-xl">
                    @foreach (['active', 'disabled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', 'active') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </label>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary rounded-xl">حفظ</button>
                <a href="{{ route('seller.packages.index') }}" class="btn btn-ghost rounded-xl">إلغاء</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
