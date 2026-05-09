<x-layouts.dashboard title="تعديل بيانات العميل" description="{{ $customer->name }}" dashboardType="admin">

    <x-ui.panel title="الحقول">
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')
            <label class="form-control">
                <span class="label-text admin-form-label">الاسم</span>
                <input name="name" value="{{ old('name', $customer->name) }}" class="input input-bordered admin-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">البريد</span>
                <input name="email" value="{{ old('email', $customer->email) }}" class="input input-bordered admin-form-input" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">الهاتف</span>
                <input name="phone" value="{{ old('phone', $customer->phone) }}" class="input input-bordered admin-form-input" />
            </label>
            <label class="form-control">
                <span class="label-text admin-form-label">الحالة</span>
                <select name="status" class="select select-bordered admin-form-input">
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
