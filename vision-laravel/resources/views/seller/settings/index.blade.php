<x-layouts.dashboard title="إعدادات البائع" dashboardType="seller">
    <x-ui.page-header title="الإعدادات" description="حساب المدير وبيانات النشاط التجاري." />

    @if (session('success'))
        <x-ui.alert tone="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.panel title="حساب المدير" class="rounded-[1.5rem]">
            <form method="POST" action="{{ route('seller.settings.profile') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <label class="form-control">
                    <span class="label-text">الاسم</span>
                    <input name="name" value="{{ old('name', $user->name) }}" class="input input-bordered rounded-xl" required />
                </label>
                <label class="form-control">
                    <span class="label-text">الهاتف</span>
                    <input name="phone" value="{{ old('phone', $user->phone) }}" class="input input-bordered rounded-xl" required />
                </label>
                <label class="form-control">
                    <span class="label-text">البريد (اختياري)</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input input-bordered rounded-xl" />
                </label>
                <button type="submit" class="btn btn-primary rounded-xl">حفظ الحساب</button>
            </form>
        </x-ui.panel>

        <x-ui.panel title="النشاط التجاري" class="rounded-[1.5rem]">
            <form method="POST" action="{{ route('seller.settings.business') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <label class="form-control">
                    <span class="label-text">اسم المتجر / الشركة</span>
                    <input name="business_name" value="{{ old('business_name', $seller->name) }}" class="input input-bordered rounded-xl" required />
                </label>
                <label class="form-control">
                    <span class="label-text">هاتف النشاط</span>
                    <input name="business_phone" value="{{ old('business_phone', $seller->phone) }}" class="input input-bordered rounded-xl" />
                </label>
                <p class="text-xs text-slate-500">نسبة العمولة الحالية: <strong>{{ $seller->commission_rate }}٪</strong> — تعديلها من قبل الإدارة فقط.</p>
                <button type="submit" class="btn btn-secondary rounded-xl">حفظ النشاط</button>
            </form>
        </x-ui.panel>
    </div>
</x-layouts.dashboard>
