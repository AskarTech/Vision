<x-layouts.dashboard title="إدارة المستخدمين" description="عرض وإدارة حسابات العملاء" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">المستخدمين</x-ui.badge>
    </x-slot>

    {{-- Metrics Summary --}}
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-6">
        <x-ui.metric-card label="إجمالي العملاء" :value="number_format($stats['total'] ?? 0)" caption="عميل مسجل" tone="blue" />
        <x-ui.metric-card label="عملاء نشطين" :value="number_format($stats['active'] ?? 0)" caption="حساب نشط" tone="emerald" />
        <x-ui.metric-card label="موقوفين" :value="number_format($stats['suspended'] ?? 0)" caption="حساب موقوف" tone="amber" />
        <x-ui.metric-card label="جدد اليوم" :value="number_format($stats['today'] ?? 0)" caption="تسجيل جديد" tone="teal" />
    </section>

    {{-- Filters and Actions --}}
    <x-ui.panel>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو البريد..."
                       class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400" />

                <select name="status" class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>محظور</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400" />

                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400" />

                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost btn-sm text-slate-300">إعادة تعيين</a>
            </form>
        </div>

        {{-- Data Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">العميل</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">البريد الإلكتروني</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الهاتف</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الرصيد</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">التسجيل</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td>
                            <div class="font-medium text-white">{{ $customer->name }}</div>
                        </td>
                        <td class="text-sm text-slate-300">{{ $customer->email ?? '-' }}</td>
                        <td class="text-sm text-slate-300">{{ $customer->phone ?? '-' }}</td>
                        <td>
                            <span class="font-bold text-emerald-400">{{ number_format($customer->wallet?->balance ?? 0, 2) }}</span>
                            <span class="text-xs text-slate-400">ريال</span>
                        </td>
                        <td>
                            <x-ui.badge tone="{{ $customer->status === 'active' ? 'emerald' : ($customer->status === 'suspended' ? 'amber' : 'rose') }}">
                                {{ $customer->status }}
                            </x-ui.badge>
                        </td>
                        <td class="text-sm text-slate-400">
                            {{ $customer->created_at?->format('Y-m-d') ?? '-' }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-ghost btn-xs text-slate-300">عرض</a>
                                @if($customer->status === 'active')
                                <form method="POST" action="{{ route('admin.customers.suspend', $customer) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-xs">إيقاف</button>
                                </form>
                                @elseif($customer->status === 'suspended')
                                <form method="POST" action="{{ route('admin.customers.activate', $customer) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-xs">تفعيل</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-400">لا يوجد عملاء لعرضهم</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
