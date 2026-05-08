<x-layouts.dashboard title="تقرير المبيعات" dashboardType="admin">
    <x-ui.page-header title="مبيعات البائعين" />
    <x-ui.panel>
        <div class="overflow-x-auto">
            <table class="table">
                <thead><tr><th>البائع</th><th>عدد البطاقات</th><th>الإيراد</th></tr></thead>
                <tbody>
                @forelse($salesBySeller as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->cards_sold }}</td>
                        <td>{{ number_format((float)($row->revenue ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">لا توجد بيانات</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
