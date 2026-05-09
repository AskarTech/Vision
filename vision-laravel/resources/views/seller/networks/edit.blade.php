<x-layouts.dashboard title="تعديل الشبكة" description="{{ $network->name }}" dashboardType="seller">

    <x-ui.panel title="تحديث البيانات">
        <form method="POST" action="{{ route('seller.networks.update', $network) }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">اسم الشبكة</span>
                <input name="name" value="{{ old('name', $network->name) }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">المعرّف (slug)</span>
                <input name="slug" value="{{ old('slug', $network->slug) }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">رمز المزود</span>
                <input name="provider_code" value="{{ old('provider_code', $network->provider_code) }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الحالة</span>
                <select name="status" class="select select-bordered vision-form-input">
                    @foreach (['active', 'disabled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $network->status) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex flex-wrap gap-2 md:col-span-2">
                <button type="submit" class="btn btn-primary">تحديث</button>
                <a href="{{ route('seller.networks.index') }}" class="vision-outline-btn">رجوع</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
