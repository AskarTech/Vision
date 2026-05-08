<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['network', 'seller'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('network_id')) {
            $query->where('network_id', $request->network_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $packages = $query->paginate(20);

        $stats = [
            'total' => Package::count(),
            'active' => Package::where('status', 'active')->count(),
            'inactive' => Package::where('status', 'inactive')->count(),
            'by_type' => Package::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
        ];

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    public function show(Package $package)
    {
        $this->authorize('view', $package);

        $package->load(['network', 'seller', 'cards']);

        return view('admin.packages.show', compact('package'));
    }

    public function create()
    {
        $this->authorize('create', Package::class);

        $sellers = \App\Models\Seller::where('status', 'active')->get();
        $networks = \App\Models\Network::where('status', 'active')->get();

        return view('admin.packages.create', compact('sellers', 'networks'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Package::class);

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'network_id' => 'required|exists:networks,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:packages,code|max:50',
            'type' => 'required|in:physical,virtual',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully');
    }

    public function edit(Package $package)
    {
        $this->authorize('update', $package);

        $sellers = \App\Models\Seller::where('status', 'active')->get();
        $networks = \App\Models\Network::where('status', 'active')->get();

        return view('admin.packages.edit', compact('package', 'sellers', 'networks'));
    }

    public function update(Request $request, Package $package)
    {
        $this->authorize('update', $package);

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'network_id' => 'required|exists:networks,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:packages,code,' . $package->id . '|max:50',
            'type' => 'required|in:physical,virtual',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Package updated successfully');
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);

        if ($package->cards()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete package with existing cards');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully');
    }
}
