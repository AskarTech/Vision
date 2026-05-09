<x-layouts.auth title="تسجيل الدخول | Vision"
    description="بوابة دخول واحدة للمسؤولين ومدراء الشركاء والعملاء — يتم التوجيه تلقائياً بعد المصادقة.">
    <div class="rounded-[1.5rem] border border-slate-200/80 bg-white p-8 shadow-[0_25px_80px_rgba(0,0,0,0.35)] sm:p-12">
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-6 flex h-[70px] w-[70px] items-center justify-center rounded-[1.25rem] bg-gradient-to-br from-[#00bdae] to-[#7338a2] text-3xl font-extrabold text-white shadow-[0_10px_30px_rgba(0,189,174,0.35)]">
                V
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800">لوحة تحكم Vision</h2>
            <p class="mt-2 text-sm leading-7 text-slate-600">أدخل بيانات الدخول للمتابعة</p>
        </div>

        <div class="space-y-6">
            @if ($errors->any())
                <x-ui.alert tone="error">{{ $errors->first() }}</x-ui.alert>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('login.store') }}">
                @csrf

                <x-ui.field label="الهاتف أو البريد الإلكتروني">
                    <input id="identifier" name="identifier" value="{{ old('identifier') }}" required
                        autocomplete="username" dir="ltr"
                        class="input input-bordered w-full border-slate-200 bg-white text-slate-800 placeholder:text-slate-400 focus:border-[#00bdae] focus:outline-none focus:ring-[3px] focus:ring-[rgba(0,189,174,0.15)]"
                        placeholder="+967… أو البريد الإلكتروني" />
                </x-ui.field>

                <x-ui.field label="كلمة المرور">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="input input-bordered w-full border-slate-200 bg-white text-slate-800 focus:border-[#00bdae] focus:outline-none focus:ring-[3px] focus:ring-[rgba(0,189,174,0.15)]" />
                </x-ui.field>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="label cursor-pointer justify-start gap-3 px-0">
                        <input type="checkbox" name="remember" value="1"
                            class="checkbox checkbox-primary checkbox-sm" />
                        <span class="label-text text-sm text-slate-600">تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block border-0 py-4 text-base font-bold">
                    تسجيل الدخول
                </button>
            </form>

            <div class="border-t border-slate-200 pt-5 text-center text-sm text-slate-600">
                <span class="text-slate-500">جديد على المنصة؟</span>
                <a href="{{ route('register') }}" class="mr-2 font-semibold text-[#00968a] hover:text-[#00bdae]">إنشاء
                    حساب عميل</a>
                <span class="text-slate-400">·</span>
                <a href="{{ route('register.seller.create') }}"
                    class="mr-2 font-semibold text-[#7338a2] hover:opacity-90">تسجيل شريك بيع</a>
            </div>

            <p class="text-center text-xs leading-6 text-slate-500">
                <a href="{{ route('password.request') }}"
                    class="underline-offset-4 hover:text-slate-700 hover:underline">نسيت كلمة المرور؟</a>
            </p>
        </div>
    </div>
</x-layouts.auth>
