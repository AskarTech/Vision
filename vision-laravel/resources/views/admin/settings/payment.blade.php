<x-layouts.dashboard title="إعدادات الدفع" dashboardType="admin">
    <x-ui.page-header title="بوابات الدفع" />
    <x-ui.panel>
        <form method="POST" action="{{ route('admin.settings.update-payment') }}" class="space-y-4">
            @csrf
            <select name="gateway_id" class="select select-bordered w-full">
                @foreach($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                @endforeach
            </select>
            <label class="label cursor-pointer w-fit gap-2">
                <input type="checkbox" name="enabled" value="1" class="toggle toggle-primary" />
                <span class="label-text">تفعيل البوابة</span>
            </label>
            <button class="btn btn-primary">حفظ</button>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
