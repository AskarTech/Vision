<x-layouts.dashboard title="تقرير العملاء" dashboardType="admin">
    <x-ui.page-header title="أفضل العملاء" />
    <x-ui.panel>
        <div class="overflow-x-auto">
            <table class="table">
                <thead><tr><th>العميل</th><th>عدد الطلبات</th><th>إجمالي الإنفاق</th></tr></thead>
                <tbody>
                @forelse($topCustomers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->total_orders ?? 0 }}</td>
                        <td>{{ number_format((float)($customer->total_spent ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">لا توجد بيانات</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
