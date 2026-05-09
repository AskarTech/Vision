<x-layouts.dashboard title="إعدادات الأمان" dashboardType="admin">
    <x-ui.panel title="سياسات الوصول">
        <form method="POST" action="{{ route('admin.settings.update-security') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text admin-form-label">مهلة الجلسة (دقيقة)</span>
                <input type="number" name="session_timeout" class="input input-bordered admin-form-input" placeholder="Session timeout (minutes)" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">حد محاولات الدخول</span>
                <input type="number" name="max_login_attempts" class="input input-bordered admin-form-input" placeholder="Max login attempts" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">الحد الأدنى لطول كلمة المرور</span>
                <input type="number" name="password_min_length" class="input input-bordered admin-form-input" placeholder="Password minimum length" />
            </label>
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
