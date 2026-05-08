<x-layouts.dashboard title="إضافة باقة" dashboardType="admin">
    <x-ui.page-header title="إضافة باقة جديدة" />
    @include('admin.packages.partials.form', [
        'action' => route('admin.packages.store'),
        'method' => 'POST',
        'package' => null,
        'sellers' => $sellers,
        'networks' => $networks,
    ])
</x-layouts.dashboard>
