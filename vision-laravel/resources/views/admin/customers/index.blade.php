<x-layouts.dashboard title="إدارة العملاء" description="عرض وإدارة حسابات العملاء" dashboardType="admin">
    <x-slot name="badge">
        <x-ui.badge tone="info">العملاء</x-ui.badge>
    </x-slot>

    <section class="admin-grid-stats">
        <x-ui.metric-card label="إجمالي العملاء" :value="number_format($stats['total'] ?? 0)" caption="عميل مسجل" tone="blue" />
        <x-ui.metric-card label="عملاء نشطين" :value="number_format($stats['active'] ?? 0)" caption="حساب نشط" tone="teal" />
        <x-ui.metric-card label="موقوفين" :value="number_format($stats['suspended'] ?? 0)" caption="حساب موقوف" tone="amber" />
        <x-ui.metric-card label="جدد اليوم" :value="number_format($stats['today'] ?? 0)" caption="تسجيل جديد" tone="teal" />
    </section>

    <x-ui.panel title="قائمة العملاء" description="بحث، تصفية وإدارة الحسابات">
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو البريد..."
                    class="admin-filter-field min-w-[200px]" />

                <select name="status" class="admin-filter-field">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>محظور</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-filter-field" />

                <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-filter-field" />

                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
                <a href="{{ route('admin.customers.index') }}" class="admin-outline-btn">إعادة تعيين</a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">العميل</th>
                        <th scope="col">البريد الإلكتروني</th>
                        <th scope="col">الهاتف</th>
                        <th scope="col">الرصيد</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التسجيل</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $customer->name }}</div>
                            </td>
                            <td class="font-normal text-slate-600">{{ $customer->email ?? '-' }}</td>
                            <td class="font-normal text-slate-600">{{ $customer->phone ?? '-' }}</td>
                            <td>
                                <span class="font-bold text-emerald-700">{{ number_format($customer->wallet?->balance ?? 0, 2) }}</span>
                                <span class="text-xs font-normal text-slate-500">ريال</span>
                            </td>
                            <td>
                                <x-ui.badge tone="{{ $customer->status === 'active' ? 'emerald' : ($customer->status === 'suspended' ? 'amber' : 'rose') }}">
                                    {{ $customer->status }}
                                </x-ui.badge>
                            </td>
                            <td class="font-normal text-slate-600">
                                {{ $customer->created_at?->format('Y-m-d') ?? '-' }}
                            </td>
                            <td>
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="admin-outline-btn">عرض</a>
                                    @if ($customer->status === 'active')
                                        <form method="POST" action="{{ route('admin.customers.suspend', $customer) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-xs">إيقاف</button>
                                        </form>
                                    @elseif ($customer->status === 'suspended')
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
                            <td colspan="7" class="py-10 text-center font-normal text-slate-500">لا يوجد عملاء لعرضهم</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
