<x-layouts.dashboard title="تهيئة البريد" description="خادم SMTP والمرسل" dashboardType="admin">
    <x-ui.panel title="المعاملات">
        <form method="POST" action="{{ route('admin.settings.update-email') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text admin-form-label">خادم SMTP</span>
                <input name="mail_host" class="input input-bordered admin-form-input" value="{{ old('mail_host', $settings['mail_host']) }}" placeholder="Host" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">المنفذ</span>
                <input name="mail_port" type="number" class="input input-bordered admin-form-input" value="{{ old('mail_port', $settings['mail_port']) }}" placeholder="Port" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">عنوان المرسل</span>
                <input name="mail_from_address" class="input input-bordered admin-form-input" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" placeholder="From Address" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">اسم المرسل</span>
                <input name="mail_from_name" class="input input-bordered admin-form-input" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" placeholder="From Name" />
            </label>
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
