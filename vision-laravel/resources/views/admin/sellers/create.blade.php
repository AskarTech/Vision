<x-layouts.dashboard title="إضافة بائع" dashboardType="admin">
    <x-ui.page-header title="إضافة بائع جديد" />
    @include('admin.sellers.partials.form', [
        'action' => route('admin.sellers.store'),
        'method' => 'POST',
        'seller' => null,
    ])
</x-layouts.dashboard>
