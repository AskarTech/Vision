<x-layouts.dashboard title="تعديل العميل" dashboardType="admin">
    <x-ui.page-header title="تعديل بيانات العميل" description="{{ $customer->name }}" />

    <x-ui.panel>
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')
            <label class="form-control">
                <span class="label-text">الاسم</span>
                <input name="name" value="{{ old('name', $customer->name) }}" class="input input-bordered" required />
            </label>
            <label class="form-control">
                <span class="label-text">البريد</span>
                <input name="email" value="{{ old('email', $customer->email) }}" class="input input-bordered" />
            </label>
            <label class="form-control">
                <span class="label-text">الهاتف</span>
                <input name="phone" value="{{ old('phone', $customer->phone) }}" class="input input-bordered" />
            </label>
            <label class="form-control">
                <span class="label-text">الحالة</span>
                <select name="status" class="select select-bordered">
                    @foreach(['active', 'suspended', 'banned'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $customer->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
