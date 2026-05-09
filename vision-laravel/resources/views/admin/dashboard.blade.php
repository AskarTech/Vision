<x-layouts.dashboard title="لوحة المعلومات" description="مرحباً بك، إليك ملخص لأداء المنصة والشركاء اليوم." dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">عمليات المشرف</x-ui.badge>
    </x-slot>

    <section x-show="activeSection === 'view-dashboard'" x-cloak class="admin-grid-stats">
        <x-ui.metric-card label="المستخدمون المسجلون" :value="number_format($metrics['users'])" caption="إجمالي الحسابات النشطة" tone="teal" />
        <x-ui.metric-card label="البائعون النشطون" :value="number_format($metrics['sellers'])" caption="الشركاء على المنصة" tone="blue" />
        <x-ui.metric-card label="الطلبات اليوم" :value="number_format($metrics['orders_today'])" caption="العمليات المنفذة خلال اليوم"
            tone="amber" />
        <x-ui.metric-card label="حركات المحفظة" :value="number_format($metrics['wallet_transactions'])" caption="سجل مالي قابل للتتبع" tone="rose" />
    </section>

    <section id="view-audit" x-show="activeSection === 'view-audit'" x-cloak class="mt-8 space-y-6">
        <x-ui.panel title="تدقيق الحسابات" description="عمليات المراجعة والسجلات الأخيرة">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="admin-list-card">
                    <h4 class="font-extrabold text-slate-800">سجل الأنشطة</h4>
                    <p class="mt-2 text-sm text-slate-600">سجل العمليات المالية والمراجعات الأخيرة.</p>
                    <div class="mt-4 space-y-0 divide-y divide-[#e2e8f0]">
                        @forelse ($recentOrders as $order)
                            <div class="flex items-center justify-between py-3">
                                <div class="text-sm font-semibold text-slate-800">{{ $order->user?->name ?? 'مستخدم' }}</div>
                                <div class="text-xs text-slate-500">{{ $order->created_at?->format('Y-m-d') ?? '' }}</div>
                            </div>
                        @empty
                            <div class="py-4 text-sm text-slate-500">لا توجد سجلات لعرضها.</div>
                        @endforelse
                    </div>
                </div>

                <div class="admin-list-card">
                    <h4 class="font-extrabold text-slate-800">تقارير سريعة</h4>
                    <p class="mt-2 text-sm text-slate-600">استخراج CSV، فلترة زمنية وتصدير سريع.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('admin.inventory.export') }}" class="admin-outline-btn">تصدير مخزون CSV</a>
                        <a href="{{ route('admin.audit.index') }}" class="admin-outline-btn">الجرد والمحاسبة</a>
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-deposits" x-show="activeSection === 'view-deposits'" x-cloak class="mt-8">
        <x-ui.panel title="إدارة الإيداعات" description="قائمة طلبات التوب أب">
            <div class="space-y-3">
                @forelse ($pendingTopups as $topup)
                    <div class="flex items-center justify-between rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-800">{{ $topup->user?->name ?? 'مستخدم' }}</div>
                            <div class="text-xs text-slate-500">{{ $topup->reference_code ?? 'بدون مرجع' }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-slate-800">{{ number_format((float) $topup->amount, 2) }} ريال</span>
                            <x-ui.badge tone="{{ $topup->status }}">{{ $topup->status }}</x-ui.badge>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">لا توجد إيداعات معلقة.</div>
                @endforelse
            </div>
        </x-ui.panel>
    </section>

    <section id="view-customers" x-show="activeSection === 'view-customers'" x-cloak class="mt-8">
        <x-ui.panel title="إدارة العملاء" description="إدارة الحسابات">
            <p class="text-sm text-slate-600">انتقل إلى صفحة العملاء للبحث والفلترة والتعديل.</p>
            <div class="mt-4">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-primary btn-sm">فتح قائمة العملاء</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-partners" x-show="activeSection === 'view-partners'" x-cloak class="mt-8">
        <x-ui.panel title="الشركاء (التجار)" description="قوائم البائعين والشركاء">
            <p class="text-sm text-slate-600">راجع الشركاء والحالة والشبكات المرتبطة.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-primary btn-sm">قائمة الشركاء</a>
                <a href="{{ route('admin.networks.index') }}" class="admin-outline-btn">الشبكات</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-managers" x-show="activeSection === 'view-managers'" x-cloak class="mt-8">
        <x-ui.panel title="مدراء الشبكات" description="إدارة مشرفي الشبكات">
            <p class="text-sm text-slate-600">يُدار ربط المدراء من بوابة الشريك. يمكنك مراجعة الشركاء والشبكات من القائمة.</p>
            <div class="mt-4">
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-primary btn-sm">الشركاء والشبكات</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-products" x-show="activeSection === 'view-products'" x-cloak class="mt-8">
        <x-ui.panel title="إدارة الباقات" description="حزم الشبكات والكروت">
            <p class="text-sm text-slate-600">إنشاء الباقات وربطها بالشبكات.</p>
            <div class="mt-4">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-primary btn-sm">فتح الباقات</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-inventory" x-show="activeSection === 'view-inventory'" x-cloak class="mt-8">
        <x-ui.panel title="أكواد المخزون" description="نظام الحجز والبيع">
            <p class="text-sm text-slate-600">عرض المخزون مع حالات: متاح، محجوز، مباع.</p>
            <div class="mt-4">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary btn-sm">فتح المخزون</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-disputes" x-show="activeSection === 'view-disputes'" x-cloak class="mt-8">
        <x-ui.panel title="البلاغات" description="الشكاوى وعمليات المطالبة">
            <p class="text-sm text-slate-600">فلترة النزاعات بحسب الحالة والمراجعة الإدارية.</p>
            <div class="mt-4">
                <a href="{{ route('admin.disputes.index') }}" class="btn btn-primary btn-sm">فتح البلاغات</a>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-finance" x-show="activeSection === 'view-finance'" x-cloak class="mt-8 mb-8">
        <x-ui.panel title="المالية" description="ملخص الحركات والسحوبات">
            <div class="admin-grid-stats mb-0">
                <x-ui.metric-card label="حركات المحفظة (مرجع)" :value="number_format($metrics['wallet_transactions'] ?? 0)" tone="slate" />
                <x-ui.metric-card label="إيداعات معلقة" :value="number_format($pendingTopups->count())" tone="teal" />
                <x-ui.metric-card label="السحوبات المعلقة" :value="number_format($metrics['withdrawals_pending'] ?? 0)" tone="amber" />
            </div>
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('admin.topups.index') }}" class="admin-outline-btn">الإيداعات</a>
                <a href="{{ route('admin.withdrawals.index') }}" class="admin-outline-btn">السحوبات</a>
                <a href="{{ route('admin.reports.index') }}" class="admin-outline-btn">التقارير المالية</a>
            </div>
        </x-ui.panel>
    </section>

    <section x-show="activeSection === 'view-dashboard'" x-cloak class="mt-8 grid gap-6 xl:grid-cols-2">
        <x-ui.panel title="المراجعات المالية" description="أحدث طلبات التوب أب والسحوبات التي تحتاج قرارًا سريعًا.">
            <div class="space-y-8">
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">طلبات التوب أب</h3>
                    <div class="space-y-3">
                        @forelse ($pendingTopups as $topup)
                            <article class="admin-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $topup->user?->name ?? 'مستخدم' }}</p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ number_format((float) $topup->amount, 2) }} ريال ·
                                            {{ $topup->reference_code ?? 'بدون مرجع' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $topup->status }}">{{ $topup->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد طلبات توب أب معلقة.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-[#e2e8f0]"></div>

                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">السحوبات</h3>
                    <div class="space-y-3">
                        @forelse ($pendingWithdrawals as $withdrawal)
                            <article class="admin-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $withdrawal->seller?->name ?? 'بائع' }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ number_format((float) $withdrawal->amount, 2) }} ريال ·
                                            {{ $withdrawal->bank_name ?? 'بدون بنك' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $withdrawal->status }}">{{ $withdrawal->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد سحوبات معلقة.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>

        <x-ui.panel title="الأوامر والتنبيهات" description="آخر الأوامر والتنبيهات التي تستحق الانتباه أو المراجعة.">
            <div class="space-y-8">
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">آخر الأوامر</h3>
                    <div class="space-y-3">
                        @forelse ($recentOrders as $order)
                            <article class="admin-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $order->user?->name ?? 'مستخدم' }}</p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $order->external_reference ?? 'بدون مرجع' }} ·
                                            {{ $order->paid_at?->format('Y-m-d H:i') ?? $order->created_at?->format('Y-m-d H:i') }}
                                        </p>
                                    </div>
                                    <x-ui.badge tone="{{ $order->status }}">{{ $order->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد طلبات حديثة.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-[#e2e8f0]"></div>

                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">التنبيهات</h3>
                    <div class="space-y-3">
                        @forelse ($recentAlerts as $alert)
                            <article class="admin-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $alert->code }}</p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $alert->report_reason ?? 'مراجعة جودة' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $alert->status }}">{{ $alert->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد تنبيهات حالية.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>

</x-layouts.dashboard>
