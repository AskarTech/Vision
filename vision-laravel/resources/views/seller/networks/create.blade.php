<x-layouts.dashboard title="إضافة شبكة" description="أنشئ شبكة جديدة ضمن حسابك فقط." dashboardType="seller">

    <x-ui.panel title="بيانات الشبكة">
        <form method="POST" action="{{ route('seller.networks.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">اسم الشبكة</span>
                <input name="name" value="{{ old('name') }}" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">المعرّف (slug) — اختياري</span>
                <input name="slug" value="{{ old('slug') }}" class="input input-bordered vision-form-input" placeholder="يُولَّد تلقائياً إن تُرك فارغاً" />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">رمز المزود</span>
                <input name="provider_code" value="{{ old('provider_code') }}" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الحالة</span>
                <select name="status" class="select select-bordered vision-form-input">
                    <option value="active" @selected(old('status', 'active') === 'active')>نشطة</option>
                    <option value="disabled" @selected(old('status') === 'disabled')>معطّلة</option>
                </select>
            </label>
            <div class="flex flex-wrap gap-2 md:col-span-2">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ route('seller.networks.index') }}" class="vision-outline-btn">إلغاء</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
