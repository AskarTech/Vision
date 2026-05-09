<x-layouts.auth title="إنشاء حساب عميل | Vision"
    description="تسجيل سريع بيانات الحساب — الاسم والهاتف وكلمة المرور — ثم الانتقال مباشرة إلى سوق البطاقات.">
    <div
        class="overflow-hidden rounded-[1.5rem] border border-white/10 bg-white/5 p-6 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.25)] backdrop-blur sm:p-8">
        <form method="POST" action="{{ route('register.store') }}" class="grid gap-4">
            @csrf
            <x-ui.field label="الاسم الكامل">
                <input name="name" value="{{ old('name') }}"
                    class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" required maxlength="255" />
            </x-ui.field>
            <x-ui.field label="رقم الهاتف">
                <input name="phone" value="{{ old('phone') }}" dir="ltr" maxlength="20" required
                    class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
            </x-ui.field>
            <x-ui.field label="البريد الإلكتروني (اختياري)" hint="يمكن إضافته لاحقاً من ملفي الشخصي">
                <input type="email" name="email" value="{{ old('email') }}" dir="ltr"
                    class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
            </x-ui.field>
            <x-ui.field label="كلمة المرور">
                <input type="password" name="password" required minlength="6" autocomplete="new-password"
                    class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
            </x-ui.field>
            <x-ui.field label="تأكيد كلمة المرور">
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                    class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
            </x-ui.field>
            <button type="submit"
                class="btn mt-2 border-0 bg-gradient-to-r from-emerald-400 to-sky-500 text-slate-950 hover:from-emerald-300 hover:to-sky-400">إنشاء الحساب</button>
        </form>
        <div class="mt-6 border-t border-white/10 pt-6 text-center text-sm text-slate-400">
            <p><a href="{{ route('login') }}" class="text-slate-200 underline-offset-4 hover:text-white hover:underline">لديك حساب بالفعل؟ تسجيل الدخول</a></p>
            <p class="mt-3"><a href="{{ route('register.seller.create') }}" class="text-sky-300 hover:text-sky-200">تسجيل كشريك بيع مع شبكتك</a></p>
        </div>
    </div>
</x-layouts.auth>
