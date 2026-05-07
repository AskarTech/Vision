<x-layouts.auth title="تسجيل الدخول | YemenWi-Fi Hub"
    description="لوحة دخول موحدة للأدمن، العملاء، ومديري الشبكات. استخدم الهاتف أو البريد الإلكتروني مع كلمة المرور.">
    <div class="overflow-hidden rounded-4xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur">
        <div class="border-b border-white/10 px-6 py-7 text-center sm:px-8">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-linear-to-br from-emerald-400 to-sky-500 text-xl font-black text-slate-950 shadow-lg">
                YH
            </div>
            <h2 class="text-2xl font-bold text-white">تسجيل الدخول</h2>
            <p class="mt-2 text-sm leading-7 text-slate-300">اختر نوع الحساب ثم أدخل الهاتف أو البريد الإلكتروني وكلمة
                المرور.</p>
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
                            <option value="admin" @selected(old('role', 'admin') === 'admin')>أدمن</option>
                            <option value="customer" @selected(old('role') === 'customer')>عميل</option>
                            <option value="seller_manager" @selected(old('role') === 'seller_manager')>مدير شبكة</option>
                        </select>
                    </x-ui.field>

                    <x-ui.field label="الهاتف أو البريد">
                        <input id="identifier" name="identifier" value="{{ old('identifier') }}" required
                            autocomplete="username"
                            class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none">
                    </x-ui.field>
                </div>

                <x-ui.field label="كلمة المرور">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="input input-bordered w-full border-white/10 bg-slate-900/60 text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none">
                </x-ui.field>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="label cursor-pointer justify-start gap-3 px-0">
                        <input type="checkbox" name="remember" value="1"
                            class="checkbox checkbox-primary checkbox-sm">
                        <span class="label-text text-sm text-slate-300">تذكرني</span>
                    </label>

                    <span class="text-xs leading-6 text-slate-500">هذه هي الواجهة الموحدة الحالية لجميع الأدوار.</span>
                </div>

                <button type="submit"
                    class="btn btn-primary w-full border-0 bg-linear-to-r from-emerald-400 to-sky-500 text-slate-950 hover:from-emerald-300 hover:to-sky-400">
                    دخول
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
