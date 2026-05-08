<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Http\Request;

class NetworksController extends Controller
{
    public function index(Request $request)
    {
        $query = Network::with('seller')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $networks = $query->paginate(20);

        $stats = [
            'total' => Network::count(),
            'active' => Network::where('status', 'active')->count(),
            'inactive' => Network::where('status', 'disabled')->count(),
            'by_seller' => Network::selectRaw('seller_id, COUNT(*) as count')
                ->groupBy('seller_id')
                ->count(),
        ];

        $sellers = \App\Models\Seller::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.networks.index', compact('networks', 'stats', 'sellers'));
    }

    public function show(Network $network)
    {
        $this->authorize('view', $network);

        $network->load(['seller', 'cards', 'packages']);

        return view('admin.networks.show', compact('network'));
    }

    public function create()
    {
        $this->authorize('create', Network::class);

        $sellers = \App\Models\Seller::where('status', 'active')->get();

        return view('admin.networks.create', compact('sellers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Network::class);

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:networks,slug|max:100',
            'provider_code' => 'nullable|string|max:60',
            'status' => 'required|in:active,disabled',
            'meta' => 'nullable|array',
        ]);

        Network::create($validated);

        return redirect()->route('admin.networks.index')
            ->with('success', 'Network created successfully');
    }

    public function edit(Network $network)
    {
        $this->authorize('update', $network);

        $sellers = \App\Models\Seller::where('status', 'active')->get();

        return view('admin.networks.edit', compact('network', 'sellers'));
    }

    public function update(Request $request, Network $network)
    {
        $this->authorize('update', $network);

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:networks,slug,' . $network->id . '|max:100',
            'provider_code' => 'nullable|string|max:60',
            'status' => 'required|in:active,disabled',
            'meta' => 'nullable|array',
        ]);

        $network->update($validated);

        return redirect()->route('admin.networks.show', $network)
            ->with('success', 'Network updated successfully');
    }

    public function destroy(Network $network)
    {
        $this->authorize('delete', $network);

        if ($network->cards()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete network with existing cards');
        }

        $network->delete();

        return redirect()->route('admin.networks.index')
            ->with('success', 'Network deleted successfully');
    }
}
