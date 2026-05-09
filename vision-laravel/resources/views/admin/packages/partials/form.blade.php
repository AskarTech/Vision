<x-ui.panel>
    <form method="POST" action="{{ $action }}" class="grid gap-4 md:grid-cols-2">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif
        <label class="form-control">
            <span class="label-text admin-form-label">البائع</span>
            <select name="seller_id" class="select select-bordered admin-form-input" required>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" @selected(old('seller_id', $package?->seller_id) == $seller->id)>{{ $seller->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الشبكة</span>
            <select name="network_id" class="select select-bordered admin-form-input" required>
                @foreach($networks as $network)
                    <option value="{{ $network->id }}" @selected(old('network_id', $package?->network_id) == $network->id)>{{ $network->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الاسم</span>
            <input name="name" class="input input-bordered admin-form-input" value="{{ old('name', $package?->name) }}" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">السعر</span>
            <input name="price" type="number" step="0.01" class="input input-bordered admin-form-input" value="{{ old('price', $package?->price) }}" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الكمية</span>
            <input name="amount" type="number" class="input input-bordered admin-form-input" value="{{ old('amount', $package?->amount) }}" />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الوحدة</span>
            <input name="unit" class="input input-bordered admin-form-input" value="{{ old('unit', $package?->unit) }}" />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الدورية</span>
            <select name="period_type" class="select select-bordered admin-form-input">
                @foreach(['daily', 'weekly', 'monthly'] as $period)
                    <option value="{{ $period }}" @selected(old('period_type', $package?->period_type ?? 'daily') === $period)>{{ $period }}</option>
                @endforeach
            </select>
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الفئة</span>
            <input name="category" class="input input-bordered admin-form-input" value="{{ old('category', $package?->category ?? 'best_selling') }}" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">Gradient</span>
            <input name="gradient" class="input input-bordered admin-form-input" value="{{ old('gradient', $package?->gradient) }}" />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الحالة</span>
            <select name="status" class="select select-bordered admin-form-input">
                @foreach(['active', 'disabled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $package?->status ?? 'active') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>
        <div class="md:col-span-2">
            <button class="btn btn-primary">حفظ</button>
        </div>
    </form>
</x-ui.panel>
