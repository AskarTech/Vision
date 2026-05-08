<x-layouts.dashboard title="تعديل باقة" dashboardType="admin">
    <x-ui.page-header title="تعديل الباقة" description="{{ $package->name }}" />
    @include('admin.packages.partials.form', [
        'action' => route('admin.packages.update', $package),
        'method' => 'PATCH',
        'package' => $package,
        'sellers' => $sellers,
        'networks' => $networks,
    ])
</x-layouts.dashboard>
