<x-layouts.dashboard title="إضافة باقة" description="اختر شبكة نشطة من شبكاتك." dashboardType="seller">

    <x-ui.panel title="بيانات الباقة">
        <form method="POST" action="{{ route('seller.packages.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الشبكة</span>
                <select name="network_id" class="select select-bordered vision-form-input" required>
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}" @selected(old('network_id') == $network->id)>{{ $network->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">اسم الباقة</span>
                <input name="name" value="{{ old('name') }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">السعر (ريال)</span>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الكمية (اختياري)</span>
                <input type="number" name="amount" value="{{ old('amount') }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الوحدة</span>
                <input name="unit" value="{{ old('unit') }}" class="input input-bordered vision-form-input" placeholder="GB / يوم ..." />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الدورية</span>
                <select name="period_type" class="select select-bordered vision-form-input">
                    @foreach (['daily', 'weekly', 'monthly'] as $p)
                        <option value="{{ $p }}" @selected(old('period_type', 'monthly') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الفئة</span>
                <input name="category" value="{{ old('category', 'monthly') }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">Gradient (اختياري)</span>
                <input name="gradient" value="{{ old('gradient') }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الحالة</span>
                <select name="status" class="select select-bordered vision-form-input">
                    @foreach (['active', 'disabled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', 'active') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex flex-wrap gap-2 md:col-span-2">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ route('seller.packages.index') }}" class="vision-outline-btn">إلغاء</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
