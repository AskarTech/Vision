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
                    <option value="{{ $seller->id }}" @selected(old('seller_id', $network?->seller_id) == $seller->id)>{{ $seller->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">اسم الشبكة</span>
            <input name="name" value="{{ old('name', $network?->name) }}" class="input input-bordered admin-form-input" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">Slug</span>
            <input name="slug" value="{{ old('slug', $network?->slug) }}" class="input input-bordered admin-form-input" required />
        </label>
        <label class="form-control">
            <span class="label-text admin-form-label">Provider Code</span>
            <input name="provider_code" value="{{ old('provider_code', $network?->provider_code) }}" class="input input-bordered admin-form-input" />
        </label>
        <label class="form-control md:col-span-2">
            <span class="label-text admin-form-label">الحالة</span>
            <select name="status" class="select select-bordered admin-form-input">
                @foreach(['active', 'disabled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $network?->status ?? 'active') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>
        <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
    </form>
</x-ui.panel>
