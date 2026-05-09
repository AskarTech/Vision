<x-layouts.dashboard title="تعديل الباقة" description="{{ $package->name }}" dashboardType="seller">

    <x-ui.panel title="تحديث الباقة">
        <form method="POST" action="{{ route('seller.packages.update', $package) }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الشبكة</span>
                <select name="network_id" class="select select-bordered vision-form-input" required>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected(old('network_id', $package->network_id) == $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">اسم الباقة</span>
                <input name="name" value="{{ old('name', $package->name) }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">السعر</span>
                <input type="number" step="0.01" name="price" value="{{ old('price', $package->price) }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الكمية</span>
                <input type="number" name="amount" value="{{ old('amount', $package->amount) }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الوحدة</span>
                <input name="unit" value="{{ old('unit', $package->unit) }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الدورية</span>
                <select name="period_type" class="select select-bordered vision-form-input">
                    @foreach (['daily', 'weekly', 'monthly'] as $p)
                        <option value="{{ $p }}" @selected(old('period_type', $package->period_type) === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الفئة</span>
                <input name="category" value="{{ old('category', $package->category) }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">Gradient</span>
                <input name="gradient" value="{{ old('gradient', $package->gradient) }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الحالة</span>
                <select name="status" class="select select-bordered vision-form-input">
                    @foreach (['active', 'disabled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $package->status) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex flex-wrap gap-2 md:col-span-2">
                <button type="submit" class="btn btn-primary">تحديث</button>
                <a href="{{ route('seller.packages.index') }}" class="vision-outline-btn">رجوع</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
