<x-layouts.auth title="تعيين كلمة مرور جديدة" description="قم بإدخال كلمة المرور الجديدة لحسابك.">
    <div class="rounded-4xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur sm:p-8">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" value="{{ old('email', $email) }}" class="input input-bordered w-full" placeholder="البريد الإلكتروني" required />
            <input type="password" name="password" class="input input-bordered w-full" placeholder="كلمة المرور الجديدة" required />
            <input type="password" name="password_confirmation" class="input input-bordered w-full" placeholder="تأكيد كلمة المرور" required />
            <button type="submit" class="btn btn-primary w-full">تحديث كلمة المرور</button>
        </form>
    </div>
</x-layouts.auth>
