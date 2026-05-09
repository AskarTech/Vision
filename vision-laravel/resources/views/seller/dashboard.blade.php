<x-layouts.dashboard title="لوحة البائع"
    description="إدارة الشبكات والباقات والمخزون من مكان واحد مع ملخص تشغيلي يتوافق مع أسلوب Vision."
    dashboardType="seller">
    <x-slot name="badge">
        <x-ui.badge tone="info">بوابة الشريك</x-ui.badge>
    </x-slot>

    <section class="vision-list-card mb-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-600">ملف الشريك</p>
                <h2 class="mt-1 text-2xl font-extrabold text-slate-800">{{ $seller->name }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                    {{ $seller->phone ?: 'لا يوجد رقم هاتف مسجل' }} · {{ $seller->networks_count }} شبكة ·
                    {{ $seller->packages_count }} باقة · {{ $seller->cards_count }} كرت
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                    <span class="block text-xs font-semibold text-slate-600">العمولة</span>
                    <strong class="mt-1 block text-lg font-extrabold text-slate-800">{{ number_format((float) $seller->commission_rate, 2) }}%</strong>
                </div>
                <div class="rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3">
                    <span class="block text-xs font-semibold text-slate-600">الحالة</span>
                    <div class="mt-2">
                        <x-ui.badge tone="{{ $seller->status }}">{{ $seller->status }}</x-ui.badge>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="vision-grid-stats">
        <x-ui.metric-card label="الشبكات النشطة" :value="number_format($metrics['networks_active'])" caption="الشبكات المتاحة حاليًا للبيع"
            tone="teal" />
        <x-ui.metric-card label="الباقات النشطة" :value="number_format($metrics['packages_active'])" caption="الباقات بحالة active"
            tone="blue" />
        <x-ui.metric-card label="الكروت النشطة" :value="number_format($metrics['cards_active'])" caption="المخزون الجاهز للبيع"
            tone="amber" />
        <x-ui.metric-card label="الكروت المحجوزة" :value="number_format($metrics['cards_reserved'])" caption="ضغط على الطلبات"
            tone="rose" />
        <x-ui.metric-card label="الكروت المباعة" :value="number_format($metrics['cards_sold'])" caption="المبيعات المؤكدة" tone="blue" />
        <x-ui.metric-card label="بلاغات الجودة" :value="number_format($metrics['cards_reported'])" caption="تحتاج متابعة"
            tone="rose" />
    </section>

    <section class="mt-2 grid gap-6 xl:grid-cols-2">
        <x-ui.panel title="الشبكات والباقات" description="آخر العناصر التي تديرها.">
            <div class="space-y-8">
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">الشبكات</h3>
                    <div class="space-y-3">
                        @forelse ($recentNetworks as $network)
                            <article class="vision-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $network->name }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $network->slug }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $network->status }}">{{ $network->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد شبكات بعد.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-[#e2e8f0]"></div>

                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">الباقات</h3>
                    <div class="space-y-3">
                        @forelse ($recentPackages as $package)
                            <article class="vision-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $package->name }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ number_format($package->price, 2) }}
                                            ريال · {{ $package->cards_count }} كرت</p>
                                    </div>
                                    <x-ui.badge tone="{{ $package->status }}">{{ $package->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد باقات بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>

        <x-ui.panel title="المخزون والمدراء" description="نظرة سريعة على الأكواد والفريق.">
            <div class="space-y-8">
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">الكروت</h3>
                    <div class="space-y-3">
                        @forelse ($recentCards as $card)
                            <article class="vision-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $card->code }}</p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ number_format((float) $card->price, 2) }} ريال ·
                                            {{ $card->uploaded_at?->format('Y-m-d') ?? '—' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $card->status }}">{{ $card->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا توجد كروت مرفوعة بعد.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-[#e2e8f0]"></div>

                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">المدراء</h3>
                    <div class="space-y-3">
                        @forelse ($recentManagers as $manager)
                            <article class="vision-list-card">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $manager->user?->name ?? $manager->username }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $manager->username }} ·
                                            {{ $manager->last_login_at?->format('Y-m-d H:i') ?? 'لم يسجل دخولًا بعد' }}
                                        </p>
                                    </div>
                                    <x-ui.badge tone="{{ $manager->status }}">{{ $manager->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-[#e2e8f0] px-4 py-5 text-sm text-slate-500">
                                لا يوجد مدراء مسجلون بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>
</x-layouts.dashboard>
