@props([
    'headers' => [],
    'rows' => [],
    'emptyMessage' => 'لا توجد بيانات لعرضها',
])

<div class="overflow-x-auto">
    @if(count($headers) > 0 && count($rows) > 0)
    <table class="admin-table">
        <thead>
            <tr>
                @foreach($headers as $header)
                <th scope="col">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                @foreach($row as $cell)
                <td class="whitespace-nowrap">
                    {!! $cell !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="flex items-center justify-center py-8 text-sm text-slate-500">
        {{ $emptyMessage }}
    </div>
    @endif
</div>
