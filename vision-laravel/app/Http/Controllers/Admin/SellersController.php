<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellersController extends Controller
{
    public function index(Request $request)
    {
        $query = Seller::withCount(['users', 'cards', 'packages'])->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('slug', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sellers = $query->paginate(20);

        $stats = [
            'total' => Seller::count(),
            'active' => Seller::where('status', 'active')->count(),
            'pending' => Seller::where('status', 'pending')->count(),
            'suspended' => Seller::where('status', 'suspended')->count(),
        ];

        return view('admin.sellers.index', compact('sellers', 'stats'));
    }

    public function show(Seller $seller)
    {
        $this->authorize('view', $seller);

        $seller->load(['users', 'networks', 'packages', 'cards', 'banks', 'managers']);

        return view('admin.sellers.show', compact('seller'));
    }

    public function create()
    {
        $this->authorize('create', Seller::class);

        return view('admin.sellers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Seller::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:sellers,slug|max:100',
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,active,suspended',
            'settings' => 'nullable|array',
        ]);

        Seller::create($validated);

        return redirect()->route('admin.sellers.index')
            ->with('success', __('admin.seller_created'));
    }

    public function edit(Seller $seller)
    {
        $this->authorize('update', $seller);

        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller)
    {
        $this->authorize('update', $seller);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:sellers,slug,' . $seller->id . '|max:100',
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,active,suspended',
            'settings' => 'nullable|array',
        ]);

        $seller->update($validated);

        return redirect()->route('admin.sellers.show', $seller)
            ->with('success', __('admin.seller_updated'));
    }

    public function approve(Seller $seller)
    {
        $this->authorize('approve', $seller);

        $seller->update(['status' => 'active']);

        return redirect()->back()->with('success', __('admin.seller_approved'));
    }

    public function suspend(Seller $seller)
    {
        $this->authorize('suspend', $seller);

        $seller->update(['status' => 'suspended']);

        return redirect()->back()->with('success', __('admin.seller_suspended'));
    }
}
