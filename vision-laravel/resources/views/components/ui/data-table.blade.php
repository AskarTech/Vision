@props([
    'headers' => [],
    'rows' => [],
    'emptyMessage' => 'لا توجد بيانات لعرضها',
])

<div class="overflow-x-auto">
    @if(count($headers) > 0 && count($rows) > 0)
    <table class="min-w-full divide-y divide-white/10">
        <thead>
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @foreach($rows as $row)
            <tr class="hover:bg-white/5 transition-colors">
                @foreach($row as $cell)
                <td class="px-4 py-3 text-sm text-slate-200 whitespace-nowrap">
                    {!! $cell !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="flex items-center justify-center py-8 text-sm text-slate-400">
        {{ $emptyMessage }}
    </div>
    @endif
</div>
