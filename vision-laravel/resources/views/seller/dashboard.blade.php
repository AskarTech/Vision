<x-layouts.dashboard title="لوحة البائع"
    description="إدارة الشبكات والباقات والمخزون من مكان واحد مع عرض واضح للبيانات التشغيلية الأساسية.">
    <x-slot name="badge">
        <x-ui.badge tone="active">Seller Inventory Sprint</x-ui.badge>
    </x-slot>

    <section class="mb-6 rounded-3xl border border-white/10 bg-white/5 p-5 shadow-2xl backdrop-blur">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-slate-400">ملف البائع</p>
                <h2 class="mt-1 text-2xl font-bold text-white">{{ $seller->name }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-300">
                    {{ $seller->phone ?: 'لا يوجد رقم هاتف مسجل' }} · {{ $seller->networks_count }} شبكة ·
                    {{ $seller->packages_count }} باقة · {{ $seller->cards_count }} كرت
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <span class="block text-xs text-slate-400">العمولة</span>
                    <strong
                        class="mt-1 block text-lg text-white">{{ number_format((float) $seller->commission_rate, 2) }}%</strong>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                    <span class="block text-xs text-slate-400">الحالة</span>
                    <div class="mt-2">
                        <x-ui.badge tone="{{ $seller->status }}">{{ $seller->status }}</x-ui.badge>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <x-ui.metric-card label="الشبكات النشطة" :value="number_format($metrics['networks_active'])" caption="الشبكات المتاحة حاليًا للبيع"
            tone="teal" />
        <x-ui.metric-card label="الباقات النشطة" :value="number_format($metrics['packages_active'])" caption="الباقات المتصلة بحالة active"
            tone="blue" />
        <x-ui.metric-card label="الكروت النشطة" :value="number_format($metrics['cards_active'])" caption="المخزون الجاهز للحجز والبيع"
            tone="amber" />
        <x-ui.metric-card label="الكروت المحجوزة" :value="number_format($metrics['cards_reserved'])" caption="تحذير مبكر من ضغط الطلبات"
            tone="rose" />
        <x-ui.metric-card label="الكروت المباعة" :value="number_format($metrics['cards_sold'])" caption="المبيعات المؤكدة للبائع" tone="blue" />
        <x-ui.metric-card label="الكروت المبلغ عنها" :value="number_format($metrics['cards_reported'])" caption="حالات تحتاج متابعة وجودة"
            tone="rose" />
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-ui.panel title="الشبكات والباقات" description="آخر العناصر التي يديرها البائع.">
            <div class="space-y-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">الشبكات</h3>
                    <div class="space-y-3">
                        @forelse ($recentNetworks as $network)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $network->name }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ $network->slug }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $network->status }}">{{ $network->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد شبكات بعد.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-white/10"></div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">الباقات</h3>
                    <div class="space-y-3">
                        @forelse ($recentPackages as $package)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $package->name }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ number_format($package->price, 2) }}
                                            ريال · {{ $package->cards_count }} كرت</p>
                                    </div>
                                    <x-ui.badge tone="{{ $package->status }}">{{ $package->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد باقات بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>

        <x-ui.panel title="المخزون والمدراء" description="نظرة سريعة على الكروت والفريق.">
            <div class="space-y-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">الكروت</h3>
                    <div class="space-y-3">
                        @forelse ($recentCards as $card)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $card->code }}</p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            {{ number_format((float) $card->price, 2) }} ريال ·
                                            {{ $card->uploaded_at?->format('Y-m-d') ?? '—' }}</p>
                                    </div>
                                    <x-ui.badge tone="{{ $card->status }}">{{ $card->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا توجد كروت مرفوعة بعد.</div>
                        @endforelse
                    </div>
                </div>

                <div class="divider my-0 border-white/10"></div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-300">المدراء</h3>
                    <div class="space-y-3">
                        @forelse ($recentManagers as $manager)
                            <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">
                                            {{ $manager->user?->name ?? $manager->username }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ $manager->username }} ·
                                            {{ $manager->last_login_at?->format('Y-m-d H:i') ?? 'لم يسجل دخولًا بعد' }}
                                        </p>
                                    </div>
                                    <x-ui.badge tone="{{ $manager->status }}">{{ $manager->status }}</x-ui.badge>
                                </div>
                            </article>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/10 px-4 py-5 text-sm text-slate-400">
                                لا يوجد مدراء مسجلون بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.panel>
    </section>
</x-layouts.dashboard>
