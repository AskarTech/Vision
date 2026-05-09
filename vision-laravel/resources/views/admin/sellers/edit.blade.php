<x-layouts.dashboard title="تعديل الشريك" description="{{ $seller->name }}" dashboardType="admin">
    @include('admin.sellers.partials.form', [
        'action' => route('admin.sellers.update', $seller),
        'method' => 'PATCH',
        'seller' => $seller,
    ])
</x-layouts.dashboard>
