<x-layouts.dashboard title="أفضل العملاء" description="تقرير الإنفاق وعدد الطلبات حسب العميل" dashboardType="admin">
    <x-ui.panel title="ترتيب العملاء">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">العميل</th>
                        <th scope="col">عدد الطلبات</th>
                        <th scope="col">إجمالي الإنفاق</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topCustomers as $customer)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $customer->name }}</td>
                            <td>{{ $customer->total_orders ?? 0 }}</td>
                            <td class="font-mono font-semibold text-emerald-800">{{ number_format((float) ($customer->total_spent ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-10 text-center text-slate-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
