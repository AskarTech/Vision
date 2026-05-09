<x-layouts.dashboard title="تعديل الباقة" description="{{ $package->name }}" dashboardType="admin">
    @include('admin.packages.partials.form', [
        'action' => route('admin.packages.update', $package),
        'method' => 'PATCH',
        'package' => $package,
        'sellers' => $sellers,
        'networks' => $networks,
    ])
</x-layouts.dashboard>
