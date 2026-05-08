<x-layouts.dashboard title="إضافة شبكة" dashboardType="admin">
    <x-ui.page-header title="إضافة شبكة جديدة" />
    @include('admin.networks.partials.form', [
        'action' => route('admin.networks.store'),
        'method' => 'POST',
        'network' => null,
        'sellers' => $sellers,
    ])
</x-layouts.dashboard>
