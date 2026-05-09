<x-layouts.dashboard title="الإعدادات" description="تهيئة المنصة والبوابات والبريد" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="info">الإعدادات</x-ui.badge></x-slot>

    <div class="grid gap-6 md:grid-cols-2">
        <a href="{{ route('admin.settings.general') }}" class="admin-list-card block transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <h3 class="text-lg font-extrabold text-slate-800">عام</h3>
            <p class="mt-2 text-sm text-slate-600">اسم التطبيق، الرابط، المنطقة الزمنية، اللغة الافتراضية.</p>
        </a>
        <a href="{{ route('admin.settings.payment') }}" class="admin-list-card block transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <h3 class="text-lg font-extrabold text-slate-800">الدفع</h3>
            <p class="mt-2 text-sm text-slate-600">بوابات الدفع والتهيئة.</p>
        </a>
        <a href="{{ route('admin.settings.email') }}" class="admin-list-card block transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <h3 class="text-lg font-extrabold text-slate-800">البريد</h3>
            <p class="mt-2 text-sm text-slate-600">خادم SMTP وعناوين المرسل.</p>
        </a>
        <a href="{{ route('admin.settings.security') }}" class="admin-list-card block transition hover:border-[var(--vision-teal)] hover:shadow-md">
            <h3 class="text-lg font-extrabold text-slate-800">الأمان</h3>
            <p class="mt-2 text-sm text-slate-600">سياسات الجلسات والوصول.</p>
        </a>
    </div>

    <x-ui.panel class="mt-8" title="ملاحظة">
        <p class="text-sm text-slate-600">التغييرات الحالية تُدار عبر ملفات البيئة والتهيئة؛ نماذج الصفحات الفرعية تتحقق من المدخلات وتعرض رسائل النجاح بعد الحفظ.</p>
    </x-ui.panel>
</x-layouts.dashboard>
