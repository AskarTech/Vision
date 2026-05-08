<x-layouts.dashboard title="الأدوار والصلاحيات" description="نموذج الوصول في المنصة" dashboardType="admin">
    <x-slot name="badge"><x-ui.badge tone="slate">صلاحيات</x-ui.badge></x-slot>

    <x-ui.panel>
        <div class="prose prose-invert max-w-none text-slate-300">
            <p class="text-white font-semibold">كيف تعمل الصلاحيات هنا؟</p>
            <ul class="mt-3 list-disc pr-6 space-y-2 text-sm">
                <li>يُحدَّد دور المستخدم في الحقل <code class="text-emerald-300">role</code> داخل جدول المستخدمين (مثل: <strong>admin</strong>، <strong>seller_manager</strong>، <strong>customer</strong>).</li>
                <li>المناطق المحمية تستخدم وسيط <strong>EnsureUserRole</strong> على مجموعات المسارات (مثلاً <code class="text-emerald-300">role:admin</code>).</li>
                <li>عناصر القائمة الجانبية تُفلتر أيضاً عبر Gates معرفة في <code class="text-emerald-300">config/permissions.php</code> وربطها في <code class="text-emerald-300">AppServiceProvider</code>.</li>
            </ul>
            <p class="mt-4 text-sm text-slate-400">لا يوجد محرّر واجهة لتعديل الأدوار حالياً؛ أي تغيير يكون عبر قاعدة البيانات أو سييدر الصلاحيات ثم مراجعة <code class="text-emerald-300">DashboardPolicy</code>.</p>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
