<x-layouts.dashboard title="لوحة الإدارة" description="مركز تشغيل لمراجعة الطلبات، السحوبات والتنبيهات المالية">
    <x-slot name="badge">
        <x-ui.badge tone="info">عمليات المشرف</x-ui.badge>
    </x-slot>

    <section x-show="activeSection === 'view-dashboard'" x-cloak class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card label="المستخدمون المسجلون" :value="number_format($metrics['users'])" caption="إجمالي الحسابات النشطة" tone="teal" />
        <x-ui.metric-card label="البائعون النشطون" :value="number_format($metrics['sellers'])" caption="الشركاء على المنصة" tone="blue" />
        <x-ui.metric-card label="الطلبات اليوم" :value="number_format($metrics['orders_today'])" caption="العمليات المنفذة خلال اليوم"
            tone="amber" />
        <x-ui.metric-card label="حركات المحفظة" :value="number_format($metrics['wallet_transactions'])" caption="سجل مالي قابل للتتبع" tone="rose" />
    </section>

    <!-- Additional sections copied from legacy layout -->
    <section id="view-audit" x-show="activeSection === 'view-audit'" x-cloak class="mt-8 space-y-6">
        <x-ui.panel title="تدقيق الحسابات" description="عمليات المراجعة والسجلات الأخيرة">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                    <h4 class="font-bold text-white">سجل الأنشطة</h4>
                    <p class="text-sm text-slate-400 mt-2">سجل العمليات المالية والمراجعات الأخيرة.</p>
                    <div class="mt-4">
                        @forelse($recentOrders as $order)
                            <div class="py-2 border-b border-white/5">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-slate-200">{{ $order->user?->name ?? 'مستخدم' }}</div>
                                    <div class="text-xs text-slate-400">{{ $order->created_at?->format('Y-m-d') ?? '' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-slate-400">لا توجد سجلات لعرضها.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                    <h4 class="font-bold text-white">تقارير سريعة</h4>
                    <p class="text-sm text-slate-400 mt-2">استخراج CSV، فلترة زمنية وتصدير سريع.</p>
                    <div class="mt-4 flex gap-2">
                        <button class="btn btn-outline btn-sm">تصدير CSV</button>
                        <button class="btn btn-outline btn-sm">تصدير PDF</button>
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>

    <section id="view-deposits" x-show="activeSection === 'view-deposits'" x-cloak class="mt-8">
        <x-ui.panel title="إدارة الإيداعات" description="قائمة طلبات التوب أب">
            <div class="space-y-3">
                @forelse($pendingTopups as $topup)
                    <div class="flex items-center justify-between rounded-lg bg-slate-900/30 p-3">
                        <div>
                            <div class="text-sm text-white">{{ $topup->user?->name ?? 'مستخدم' }}</div>
                            <div class="text-xs text-slate-400">{{ $topup->reference_code ?? 'بدون مرجع' }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-200">{{ number_format((float)$topup->amount,2) }} ريال</span>
                            <x-ui.badge tone="{{ $topup->status }}">{{ $topup->status }}</x-ui.badge>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-400">لا توجد إيداعات معلقة.</div>
                @endforelse
            </div>
        </x-ui.panel>
    </section>

    <section id="view-customers" x-show="activeSection === 'view-customers'" x-cloak class="mt-8">
        <x-ui.panel title="المستخدمون" description="إدارة الحسابات">
            <div class="text-sm text-slate-400">قائمة المستخدمين ستظهر هنا (قابلة للفلترة والبحث).</div>
        </x-ui.panel>
    </section>

    <section id="view-partners" x-show="activeSection === 'view-partners'" x-cloak class="mt-8">
        <x-ui.panel title="الشركاء" description="قوائم البائعين والشركاء">
            <div class="text-sm text-slate-400">قائمة الشركاء متاحة للمراجعة والتصفية.</div>
        </x-ui.panel>
    </section>

    <section id="view-managers" x-show="activeSection === 'view-managers'" x-cloak class="mt-8">
        <x-ui.panel title="مدراء الشبكات" description="إدارة مشرفي الشبكات">
            <div class="text-sm text-slate-400">قائمة المدراء وحقوق الوصول.</div>
        </x-ui.panel>
    </section>

    <section id="view-products" x-show="activeSection === 'view-products'" x-cloak class="mt-8">
        <x-ui.panel title="المنتجات" description="حزم الشبكات والكروت">
            <div class="text-sm text-slate-400">إدارة الحزم والكروت والمخزون.</div>
        </x-ui.panel>
    </section>

    <section id="view-inventory" x-show="activeSection === 'view-inventory'" x-cloak class="mt-8">
        <x-ui.panel title="المخزون" description="نظام الحجز والبيع">
            <div class="text-sm text-slate-400">عرض المخزون مع حالات: متاح، محجوز، مباع.</div>
        </x-ui.panel>
    </section>

    <section id="view-disputes" x-show="activeSection === 'view-disputes'" x-cloak class="mt-8">
        <x-ui.panel title="النزاعات" description="الشكاوى وعمليات المطالبة">
            <div class="text-sm text-slate-400">أدوات لفلترة النزاعات بحسب الحالة والأولوية.</div>
        </x-ui.panel>
    </section>

    <section id="view-finance" x-show="activeSection === 'view-finance'" x-cloak class="mt-8 mb-8">
        <x-ui.panel title="المالية" description="ملخص الحركات والسحوبات">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="metric-card-base tone-slate">
                    <div class="text-sm text-slate-400">رصيد الخزينة</div>
                    <div class="text-2xl font-black text-white">{{ number_format($metrics['wallet_transactions'] ?? 0) }}</div>
                </div>
                <div class="metric-card-base tone-teal">
                    <div class="text-sm text-slate-400">إجمالي التوب أب</div>
                    <div class="text-2xl font-black text-white">0</div>
                </div>
                <div class="metric-card-base tone-amber">
                    <div class="text-sm text-slate-400">السحوبات المعلقة</div>
                    <div class="text-2xl font-black text-white">{{ number_format($metrics['withdrawals_pending'] ?? 0) }}</div>
                </div>
            </div>
        </x-ui.panel>
    </section>

    <section x-show="activeSection === 'view-dashboard'" x-cloak class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-ui.panel title="المراجعات المالية" description="أحدث طلبات التوب أب والسحوبات التي تحتاج قرارًا سريعًا.">
            <div class="space-y-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">طلبات التوب أب</h3>
                    <div class="space-y-3">
                        @forelse ($pendingTopups as $topup)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $topup->user?->name ?? 'مستخدم' }}</p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            {{ number_format((float) $topup->amount, 2) }} ريال ·
                                            {{ $topup->reference_code ?? 'بدون مرجع' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $topup->status }}">{{ $topup->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد طلبات توب أب معلقة.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-white/10"></div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">السحوبات</h3>
                    <div class="space-y-3">
                        @forelse ($pendingWithdrawals as $withdrawal)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $withdrawal->seller?->name ?? 'بائع' }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            {{ number_format((float) $withdrawal->amount, 2) }} ريال ·
                                            {{ $withdrawal->bank_name ?? 'بدون بنك' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $withdrawal->status }}">{{ $withdrawal->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد سحوبات معلقة.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>

        <x-ui.panel title="الأوامر والتنبيهات" description="آخر الأوامر والتنبيهات التي تستحق الانتباه أو المراجعة.">
            <div class="space-y-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">آخر الأوامر</h3>
                    <div class="space-y-3">
                        @forelse ($recentOrders as $order)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $order->user?->name ?? 'مستخدم' }}</p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            {{ $order->external_reference ?? 'بدون مرجع' }} ·
                                            {{ $order->paid_at?->format('Y-m-d H:i') ?? $order->created_at?->format('Y-m-d H:i') }}
                                        </p>
                                    </div>
                                    <x-ui.badge tone="{{ $order->status }}">{{ $order->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد طلبات حديثة.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-white/10"></div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">التنبيهات</h3>
                    <div class="space-y-3">
                        @forelse ($recentAlerts as $alert)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $alert->code }}</p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            {{ $alert->report_reason ?? 'مراجعة جودة' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $alert->status }}">{{ $alert->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد تنبيهات حالية.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>

</x-layouts.dashboard>
