<x-layouts.auth title="تسجيل شريك بيع | Vision"
    description="إنشاء حساب مدير شبكة مع شبكتك الأولى ومعلومات المحفظة المحلية.">
    <div class="overflow-hidden rounded-[1.5rem] border border-white/10 bg-white/5 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.25)] backdrop-blur"
        x-data="{ step: 1 }">
        <div class="border-b border-white/10 px-6 py-6 text-center sm:px-8">
            <h2 class="text-xl font-bold text-white sm:text-2xl">تسجيل شريك بيع</h2>
            <p class="mt-2 text-sm leading-7 text-slate-300">خطوتان: بيانات النشاط ثم الشبكة والمحفظة المحلية</p>
            <div class="mx-auto mt-4 flex max-w-xs justify-center gap-2">
                <span class="h-2 flex-1 rounded-full"
                    :class="step >= 1 ? 'bg-emerald-400' : 'bg-slate-600'"></span>
                <span class="h-2 flex-1 rounded-full"
                    :class="step >= 2 ? 'bg-emerald-400' : 'bg-slate-600'"></span>
            </div>
        </div>

        <form method="POST" action="{{ route('register.seller.store') }}" class="space-y-6 px-6 py-6 sm:px-8">
            @csrf

            <div x-show="step === 1" x-cloak class="grid gap-4">
                <x-ui.field label="اسم النشاط التجاري">
                    <input name="business_name" value="{{ old('business_name') }}" required maxlength="160"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
                </x-ui.field>
                <x-ui.field label="هاتف النشاط">
                    <input name="business_phone" value="{{ old('business_phone') }}" required maxlength="20"
                        dir="ltr"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
                </x-ui.field>
                <x-ui.field label="اسم مدير الحساب">
                    <input name="manager_name" value="{{ old('manager_name') }}" required maxlength="255"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
                </x-ui.field>
                <x-ui.field label="هاتف مدير الحساب (لتسجيل الدخول)">
                    <input name="manager_phone" value="{{ old('manager_phone') }}" required maxlength="20" dir="ltr"
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
                <button type="button" @click="step = 2"
                    class="btn w-full border-0 bg-linear-to-r from-emerald-400 to-sky-500 text-slate-950">
                    التالي: الشبكة والمحفظة
                </button>
            </div>

            <div x-show="step === 2" x-cloak class="grid gap-4">
                <x-ui.field label="اسم الشبكة (العلامة)">
                    <input name="network_name" value="{{ old('network_name') }}" required maxlength="160"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
                </x-ui.field>
                <x-ui.field label="رمز المزود (اختياري)">
                    <input name="network_provider_code" value="{{ old('network_provider_code') }}" maxlength="60"
                        dir="ltr"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100" />
                </x-ui.field>
                <x-ui.field label="تسمية المحفظة المحلية (اختياري)"
                    hint="للمطابقة مع محفظتك على فلوكة أو جوالي أو غيرها">
                    <input name="wallet_display_label" value="{{ old('wallet_display_label') }}" maxlength="120"
                        placeholder="مثال: محفظة المبيعات الرئيسية"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 placeholder:text-slate-500" />
                </x-ui.field>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="button" @click="step = 1" class="btn btn-outline flex-1 border-white/20 text-white">
                        السابق
                    </button>
                    <button type="submit"
                        class="btn flex-1 border-0 bg-linear-to-r from-emerald-400 to-sky-500 text-slate-950">
                        إنشاء الحساب والشبكة
                    </button>
                </div>
            </div>
        </form>

        <p class="border-t border-white/10 px-6 py-4 text-center text-sm text-slate-400">
            <a href="{{ route('register') }}" class="text-emerald-300 hover:text-emerald-200">تسجيل كعميل</a>
            <span class="mx-2 text-slate-600">|</span>
            <a href="{{ route('login') }}" class="text-slate-300 hover:text-white">لديك حساب؟ تسجيل الدخول</a>
        </p>
    </div>
</x-layouts.auth>
