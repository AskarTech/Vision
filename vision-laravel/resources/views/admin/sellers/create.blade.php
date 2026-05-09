<x-layouts.dashboard title="إضافة شريك جديد" dashboardType="admin">
    @include('admin.sellers.partials.form', [
        'action' => route('admin.sellers.store'),
        'method' => 'POST',
        'seller' => null,
    ])
</x-layouts.dashboard>
