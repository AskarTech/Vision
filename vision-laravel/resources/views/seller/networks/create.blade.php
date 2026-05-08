<x-layouts.dashboard title="شبكة جديدة" dashboardType="seller">
    <x-ui.page-header title="إضافة شبكة" description="أنشئ شبكة جديدة ضمن حسابك فقط." />

    <x-ui.panel class="rounded-[1.5rem]">
        <form method="POST" action="{{ route('seller.networks.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text">اسم الشبكة</span>
                <input name="name" value="{{ old('name') }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control">
                <span class="label-text">المعرّف (slug) — اختياري</span>
                <input name="slug" value="{{ old('slug') }}" class="input input-bordered rounded-xl" placeholder="يُولَّد تلقائياً إن تُرك فارغاً" />
            </label>
            <label class="form-control">
                <span class="label-text">رمز المزود</span>
                <input name="provider_code" value="{{ old('provider_code') }}" class="input input-bordered rounded-xl" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">الحالة</span>
                <select name="status" class="select select-bordered rounded-xl">
                    <option value="active" @selected(old('status', 'active') === 'active')>نشطة</option>
                    <option value="disabled" @selected(old('status') === 'disabled')>معطّلة</option>
                </select>
            </label>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary rounded-xl">حفظ</button>
                <a href="{{ route('seller.networks.index') }}" class="btn btn-ghost rounded-xl">إلغاء</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
