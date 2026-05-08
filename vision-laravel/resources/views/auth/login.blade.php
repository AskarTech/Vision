<x-layouts.auth title="تسجيل الدخول | YemenWi-Fi Hub"
    description="بوابة دخول واحدة عبر الهاتف أو البريد الإلكتروني مع كلمة المرور — توجيه تلقائي حسب نوع الحساب إلى لوحة التحكم المناسبة.">
    <div
        class="overflow-hidden rounded-[1.5rem] border border-white/10 bg-white/5 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.25)] backdrop-blur">
        <div class="border-b border-white/10 px-6 py-7 text-center sm:px-8">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-gradient-to-br from-emerald-400 to-sky-500 text-xl font-black text-slate-950 shadow-lg">
                YH
            </div>
            <h2 class="text-2xl font-bold text-white">تسجيل الدخول</h2>
            <p class="mt-2 text-sm leading-7 text-slate-300">اختر نوع الحساب، ثم أدخل رقم الهاتف أو البريد الإلكتروني
                المرتبط بهذا النوع.</p>
        </div>

        <div class="space-y-6 px-6 py-6 sm:px-8 sm:py-8">
            @if ($errors->any())
                <x-ui.alert tone="error">{{ $errors->first() }}</x-ui.alert>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.field label="نوع الحساب">
                        <select id="role" name="role" required
                            class="select select-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 focus:border-emerald-400 focus:outline-none">
                            <option value="customer" @selected(old('role', 'customer') === 'customer')>عميل — سوق البطاقات
                            </option>
                            <option value="seller_manager" @selected(old('role') === 'seller_manager')>مدير شبكة بيع —
                                لوحة الشريك</option>
                            <option value="admin" @selected(old('role') === 'admin')>مسؤول — لوحة التحكم الإدارية</option>
                        </select>
                    </x-ui.field>

                    <x-ui.field label="الهاتف أو البريد الإلكتروني">
                        <input id="identifier" name="identifier" value="{{ old('identifier') }}" required
                            autocomplete="username" dir="ltr"
                            class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none"
                            placeholder="+967… أو البريد" />
                    </x-ui.field>
                </div>

                <x-ui.field label="كلمة المرور">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none" />
                </x-ui.field>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="label cursor-pointer justify-start gap-3 px-0">
                        <input type="checkbox" name="remember" value="1"
                            class="checkbox checkbox-primary checkbox-sm">
                        <span class="label-text text-sm text-slate-300">تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <button type="submit"
                    class="btn btn-primary w-full border-0 bg-gradient-to-r from-emerald-400 to-sky-500 text-slate-950 hover:from-emerald-300 hover:to-sky-400">
                    دخول
                </button>
            </form>

            <div class="border-t border-white/10 pt-4 text-center text-sm text-slate-400">
                <span class="text-slate-500">جديد على المنصة؟</span>
                <a href="{{ route('register') }}" class="mr-2 text-emerald-300 hover:text-emerald-200">إنشاء حساب عميل</a>
                <span class="text-slate-600">·</span>
                <a href="{{ route('register.seller.create') }}" class="mr-2 text-sky-300 hover:text-sky-200">تسجيل شريك بيع</a>
            </div>

            <p class="text-center text-xs leading-6 text-slate-500">
                <a href="{{ route('password.request') }}" class="underline-offset-4 hover:text-slate-300 hover:underline">نسيت كلمة المرور؟</a>
            </p>
        </div>
    </div>
</x-layouts.auth>
