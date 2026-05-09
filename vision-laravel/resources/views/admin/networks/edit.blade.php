<x-layouts.dashboard title="تعديل الشبكة" description="{{ $network->name }}" dashboardType="admin">
    @include('admin.networks.partials.form', [
        'action' => route('admin.networks.update', $network),
        'method' => 'PATCH',
        'network' => $network,
        'sellers' => $sellers,
    ])
</x-layouts.dashboard>
