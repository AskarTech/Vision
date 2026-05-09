<x-layouts.dashboard title="بوابات الدفع" description="تهيئة بوابات الدفع والتفعيل" dashboardType="admin">
    <x-ui.panel title="اختيار البوابة">
        <form method="POST" action="{{ route('admin.settings.update-payment') }}" class="space-y-4">
            @csrf
            <label class="form-control w-full max-w-md">
                <span class="label-text admin-form-label">البوابة</span>
                <select name="gateway_id" class="select select-bordered admin-form-input w-full">
                @foreach($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                @endforeach
                </select>
            </label>
            <label class="label cursor-pointer w-fit gap-2">
                <input type="checkbox" name="enabled" value="1" class="toggle toggle-primary" />
                <span class="label-text">تفعيل البوابة</span>
            </label>
            <button class="btn btn-primary">حفظ</button>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
