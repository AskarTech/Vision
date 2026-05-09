<x-layouts.dashboard title="إضافة شبكة جديدة" dashboardType="admin">
    @include('admin.networks.partials.form', [
        'action' => route('admin.networks.store'),
        'method' => 'POST',
        'network' => null,
        'sellers' => $sellers,
    ])
</x-layouts.dashboard>
