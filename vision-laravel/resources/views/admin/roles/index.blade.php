<x-layouts.dashboard title="الأدوار والصلاحيات" description="نموذج الوصول في المنصة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="slate">صلاحيات</x-ui.badge></x-slot>

    <x-ui.panel title="نظرة عامة">
        <div class="max-w-none text-slate-700">
            <p class="font-extrabold text-slate-900">كيف تعمل الصلاحيات هنا؟</p>
            <ul class="mt-3 list-disc space-y-2 pe-6 text-sm leading-relaxed">
                <li>يُحدَّد دور المستخدم في الحقل <code class="rounded bg-[#f8fafc] px-1 font-mono text-emerald-800">role</code> داخل جدول المستخدمين (مثل: <strong>admin</strong>، <strong>seller_manager</strong>، <strong>customer</strong>).</li>
                <li>المناطق المحمية تستخدم وسيط <strong>EnsureUserRole</strong> على مجموعات المسارات (مثلاً <code class="rounded bg-[#f8fafc] px-1 font-mono text-emerald-800">role:admin</code>).</li>
                <li>عناصر القائمة الجانبية تُفلتر أيضاً عبر Gates معرفة في <code class="rounded bg-[#f8fafc] px-1 font-mono text-emerald-800">config/permissions.php</code> وربطها في <code class="rounded bg-[#f8fafc] px-1 font-mono text-emerald-800">AppServiceProvider</code>.</li>
            </ul>
            <p class="mt-4 text-sm text-slate-600">لا يوجد محرّر واجهة لتعديل الأدوار حالياً؛ أي تغيير يكون عبر قاعدة البيانات أو سييدر الصلاحيات ثم مراجعة <code class="rounded bg-[#f8fafc] px-1 font-mono text-emerald-800">DashboardPolicy</code>.</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
