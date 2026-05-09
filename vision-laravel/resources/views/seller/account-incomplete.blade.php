<x-layouts.auth title="حساب الشريك غير مكتمل"
    description="لا يمكن فتح لوحة الشريك قبل ربط حسابك بملف بائع في النظام.">
    <div class="rounded-2xl border border-[#e2e8f0] bg-white px-6 py-8 shadow-[0_10px_40px_-10px_rgba(15,23,42,0.15)] sm:px-10">
        <h1 class="text-xl font-extrabold text-slate-800">تعذّر فتح لوحة الشريك</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-600">
            حسابك مُعلَّم كمدير شبكة بيع، لكنه <strong class="text-amber-800">غير مرتبط</strong> بملف شريك في قاعدة
            البيانات، أو أن ملف الشريك غير موجود. هذا غالباً ما يحدث عند إنشاء المستخدم يدوياً دون تعيين
            <span dir="ltr" class="rounded bg-[#f8fafc] px-1 font-mono text-xs text-slate-700">seller_id</span>.
        </p>
        <ul class="mt-4 list-disc space-y-2 pe-5 text-sm text-slate-600">
            <li>إن كنت مسجّلاً عبر <strong class="text-slate-800">تسجيل شريك بيع</strong>، جرّب تسجيل الخروج ثم الدخول مرة أخرى.</li>
            <li>وإلا فاطلب من مسؤول المنصة ربط حسابك بملف الشريك الصحيح من لوحة الإدارة.</li>
        </ul>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-outline w-full border-[#e2e8f0] text-slate-700 hover:bg-slate-50 sm:w-auto">
                    تسجيل الخروج
                </button>
            </form>
            <a href="{{ route('login') }}" class="btn btn-primary w-full sm:w-auto">
                العودة لتسجيل الدخول
            </a>
        </div>
    </div>
</x-layouts.auth>
