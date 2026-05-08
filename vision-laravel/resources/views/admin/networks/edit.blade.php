<x-layouts.dashboard title="تعديل شبكة" dashboardType="admin">
    <x-ui.page-header title="تعديل الشبكة" description="{{ $network->name }}" />
    @include('admin.networks.partials.form', [
        'action' => route('admin.networks.update', $network),
        'method' => 'PATCH',
        'network' => $network,
        'sellers' => $sellers,
    ])
</x-layouts.dashboard>
