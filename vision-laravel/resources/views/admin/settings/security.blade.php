<x-layouts.dashboard title="إعدادات الأمان" dashboardType="admin">
    <x-ui.page-header title="إعدادات الأمان" />
    <x-ui.panel>
        <form method="POST" action="{{ route('admin.settings.update-security') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <input type="number" name="session_timeout" class="input input-bordered" placeholder="Session timeout (minutes)" />
            <input type="number" name="max_login_attempts" class="input input-bordered" placeholder="Max login attempts" />
            <input type="number" name="password_min_length" class="input input-bordered" placeholder="Password minimum length" />
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
