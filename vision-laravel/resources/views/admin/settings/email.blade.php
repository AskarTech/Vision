<x-layouts.dashboard title="إعدادات البريد" dashboardType="admin">
    <x-ui.page-header title="تهيئة البريد" />
    <x-ui.panel>
        <form method="POST" action="{{ route('admin.settings.update-email') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <input name="mail_host" class="input input-bordered" value="{{ old('mail_host', $settings['mail_host']) }}" placeholder="Host" />
            <input name="mail_port" type="number" class="input input-bordered" value="{{ old('mail_port', $settings['mail_port']) }}" placeholder="Port" />
            <input name="mail_from_address" class="input input-bordered" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" placeholder="From Address" />
            <input name="mail_from_name" class="input input-bordered" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" placeholder="From Name" />
            <div class="md:col-span-2"><button class="btn btn-primary">حفظ</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
