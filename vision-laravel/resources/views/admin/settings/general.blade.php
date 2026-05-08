<x-layouts.dashboard title="الإعدادات العامة" dashboardType="admin">
    <x-ui.page-header title="الإعدادات العامة" />
    <x-ui.panel>
        <form method="POST" action="{{ route('admin.settings.update-general') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <input name="app_name" class="input input-bordered" value="{{ old('app_name', $settings['app_name']) }}" placeholder="اسم التطبيق" />
            <input name="app_url" class="input input-bordered" value="{{ old('app_url', $settings['app_url']) }}" placeholder="رابط التطبيق" />
            <input name="timezone" class="input input-bordered" value="{{ old('timezone', $settings['timezone']) }}" placeholder="Timezone" />
            <select name="locale" class="select select-bordered">
                @foreach(['ar', 'en'] as $locale)
                    <option value="{{ $locale }}" @selected(old('locale', $settings['locale']) === $locale)>{{ $locale }}</option>
                @endforeach
            </select>
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
