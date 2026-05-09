<x-layouts.dashboard title="تقرير المبيعات" description="مبيعات البائعين والإيراد خلال الفترة" dashboardType="admin">
    <x-ui.panel title="مبيعات الشركاء">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">البائع</th>
                        <th scope="col">عدد البطاقات</th>
                        <th scope="col">الإيراد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesBySeller as $row)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $row->name }}</td>
                            <td>{{ $row->cards_sold }}</td>
                            <td class="font-mono font-semibold text-emerald-800">{{ number_format((float) ($row->revenue ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-10 text-center text-slate-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.panel>
</x-layouts.dashboard>
