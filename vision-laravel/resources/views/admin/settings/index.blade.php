<x-layouts.dashboard title="الإعدادات" description="تهيئة المنصة والبوابات والبريد" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الإعدادات</x-ui.badge></x-slot>

    <div class="grid gap-4 md:grid-cols-2">
        <a href="{{ route('admin.settings.general') }}" class="block rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-emerald-400/40">
            <h3 class="text-lg font-bold text-white">عام</h3>
            <p class="mt-2 text-sm text-slate-400">اسم التطبيق، الرابط، المنطقة الزمنية، اللغة الافتراضية.</p>
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="block rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-emerald-400/40">
            <h3 class="text-lg font-bold text-white">الدفع</h3>
            <p class="mt-2 text-sm text-slate-400">بوابات الدفع والتهيئة.</p>
        </a>
        <a href="{{ route('admin.settings.email') }}" class="block rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-emerald-400/40">
            <h3 class="text-lg font-bold text-white">البريد</h3>
            <p class="mt-2 text-sm text-slate-400">خادم SMTP وعناوين المرسل.</p>
        </a>
        <a href="{{ route('admin.settings.security') }}" class="block rounded-2xl border border-white/10 bg-slate-950/40 p-6 transition hover:border-emerald-400/40">
            <h3 class="text-lg font-bold text-white">الأمان</h3>
            <p class="mt-2 text-sm text-slate-400">سياسات الجلسات والوصول.</p>
        </a>
    </div>

    <x-ui.panel class="mt-8">
        <p class="text-sm text-slate-400">التغييرات الحالية تُدار عبر ملفات البيئة والتهيئة؛ نماذج الصفحات الفرعية تتحقق من المدخلات وتعرض رسائل النجاح بعد الحفظ.</p>
    </x-ui.panel>
</x-layouts.dashboard>
