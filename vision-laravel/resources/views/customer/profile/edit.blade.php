<x-layouts.customer title="الملف الشخصي" description="تحديث بيانات الحساب وكلمة المرور.">
    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.panel title="البيانات الأساسية">
            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input name="name" value="{{ old('name', $user->name) }}" class="input input-bordered w-full" placeholder="الاسم" required />
                <input name="phone" value="{{ old('phone', $user->phone) }}" class="input input-bordered w-full" placeholder="الهاتف" required />
                <input name="email" value="{{ old('email', $user->email) }}" class="input input-bordered w-full" placeholder="البريد الإلكتروني" />
                <button class="btn btn-primary">حفظ البيانات</button>
            </form>
        </x-ui.panel>

        <x-ui.panel title="تغيير كلمة المرور">
            <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="password" name="current_password" class="input input-bordered w-full" placeholder="كلمة المرور الحالية" required />
                <input type="password" name="password" class="input input-bordered w-full" placeholder="كلمة المرور الجديدة" required />
                <input type="password" name="password_confirmation" class="input input-bordered w-full" placeholder="تأكيد كلمة المرور" required />
                <button class="btn btn-outline">تحديث كلمة المرور</button>
            </form>
        </x-ui.panel>
    </div>
</x-layouts.customer>
