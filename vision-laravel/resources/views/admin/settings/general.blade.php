<x-layouts.dashboard title="الإعدادات العامة" dashboardType="admin">
    <x-ui.panel title="البيانات الأساسية">
        <form method="POST" action="{{ route('admin.settings.update-general') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text admin-form-label">اسم التطبيق</span>
                <input name="app_name" class="input input-bordered admin-form-input" value="{{ old('app_name', $settings['app_name']) }}" placeholder="اسم التطبيق" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">رابط التطبيق</span>
                <input name="app_url" class="input input-bordered admin-form-input" value="{{ old('app_url', $settings['app_url']) }}" placeholder="رابط التطبيق" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">المنطقة الزمنية</span>
                <input name="timezone" class="input input-bordered admin-form-input" value="{{ old('timezone', $settings['timezone']) }}" placeholder="Timezone" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">اللغة</span>
                <select name="locale" class="select select-bordered admin-form-input">
                @foreach(['ar', 'en'] as $locale)
                    <option value="{{ $locale }}" @selected(old('locale', $settings['locale']) === $locale)>{{ $locale }}</option>
                @endforeach
                </select>
            </label>
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
