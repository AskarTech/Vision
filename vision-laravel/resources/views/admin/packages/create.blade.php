<x-layouts.dashboard title="إضافة باقة جديدة" dashboardType="admin">
    @include('admin.packages.partials.form', [
        'action' => route('admin.packages.store'),
        'method' => 'POST',
        'package' => null,
        'sellers' => $sellers,
        'networks' => $networks,
    ])
</x-layouts.dashboard>
