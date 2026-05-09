<x-ui.panel>
    <form method="POST" action="{{ $action }}" class="grid gap-4 md:grid-cols-2">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <label class="form-control">
            <span class="label-text admin-form-label">الاسم</span>
            <input name="name" value="{{ old('name', $seller?->name) }}" class="input input-bordered admin-form-input" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">المعرف المختصر</span>
            <input name="slug" value="{{ old('slug', $seller?->slug) }}" class="input input-bordered admin-form-input" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">الهاتف</span>
            <input name="phone" value="{{ old('phone', $seller?->phone) }}" class="input input-bordered admin-form-input" />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">نسبة العمولة</span>
            <input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $seller?->commission_rate ?? 0) }}" class="input input-bordered admin-form-input" required />
        </label>
        <label class="form-control md:col-span-2">
            <span class="label-text admin-form-label">الحالة</span>
            <select name="status" class="select select-bordered admin-form-input">
                @foreach(['pending', 'active', 'suspended'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $seller?->status ?? 'pending') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>
        <div class="md:col-span-2">
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</x-ui.panel>
