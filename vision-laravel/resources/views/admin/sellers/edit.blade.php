<x-layouts.dashboard title="تعديل البائع" dashboardType="admin">
    <x-ui.page-header title="تعديل البائع" description="{{ $seller->name }}" />
    @include('admin.sellers.partials.form', [
        'action' => route('admin.sellers.update', $seller),
        'method' => 'PATCH',
        'seller' => $seller,
    ])
</x-layouts.dashboard>
