<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackagesController extends Controller
{
    private function sellerId(Request $request): int
    {
        $id = $request->user()?->seller_id;
        abort_unless($id, 403);

        return (int) $id;
    }

    public function index(Request $request): View
    {
        $sellerId = $this->sellerId($request);

        $query = Package::query()
            ->where('seller_id', $sellerId)
            ->with('network')
            ->withCount('cards')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('network_id')) {
            $query->where('network_id', $request->integer('network_id'));
        }

        $packages = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Package::query()->where('seller_id', $sellerId)->count(),
            'active' => Package::query()->where('seller_id', $sellerId)->where('status', 'active')->count(),
        ];

        $networks = Network::query()
            ->where('seller_id', $sellerId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('seller.packages.index', compact('packages', 'stats', 'networks'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $this->authorize('create', Package::class);
        $sellerId = $this->sellerId($request);

        $networks = Network::query()
            ->where('seller_id', $sellerId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($networks->isEmpty()) {
            return redirect()->route('seller.networks.create')
                ->with('error', 'أنشئ شبكة نشطة أولاً ثم عد لإضافة باقة.');
        }

        return view('seller.packages.create', compact('networks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Package::class);
        $sellerId = $this->sellerId($request);

        $validated = $request->validate([
            'network_id' => [
                'required',
                Rule::exists('networks', 'id')->where(fn ($q) => $q->where('seller_id', $sellerId)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'amount' => ['nullable', 'integer', 'min:1'],
            'unit' => ['nullable', 'string', 'max:20'],
            'period_type' => ['required', 'in:daily,weekly,monthly'],
            'category' => ['required', 'string', 'max:40'],
            'gradient' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:active,disabled'],
        ]);

        $validated['seller_id'] = $sellerId;

        Package::query()->create($validated);

        return redirect()->route('seller.packages.index')
            ->with('success', 'تم إنشاء الباقة.');
    }

    public function edit(Request $request, Package $package): View
    {
        $this->authorize('update', $package);
        $sellerId = $this->sellerId($request);
        abort_unless((int) $package->seller_id === $sellerId, 404);

        $networks = Network::query()
            ->where('seller_id', $sellerId)
            ->where(function ($q) use ($package): void {
                $q->where('status', 'active')->orWhere('id', $package->network_id);
            })
            ->orderBy('name')
            ->get();

        return view('seller.packages.edit', compact('package', 'networks'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $this->authorize('update', $package);
        $sellerId = $this->sellerId($request);
        abort_unless((int) $package->seller_id === $sellerId, 404);

        $validated = $request->validate([
            'network_id' => [
                'required',
                Rule::exists('networks', 'id')->where(fn ($q) => $q->where('seller_id', $sellerId)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'amount' => ['nullable', 'integer', 'min:1'],
            'unit' => ['nullable', 'string', 'max:20'],
            'period_type' => ['required', 'in:daily,weekly,monthly'],
            'category' => ['required', 'string', 'max:40'],
            'gradient' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:active,disabled'],
        ]);

        $package->update($validated);

        return redirect()->route('seller.packages.index')
            ->with('success', 'تم تحديث الباقة.');
    }

    public function destroy(Request $request, Package $package): RedirectResponse
    {
        $this->authorize('delete', $package);
        abort_unless((int) $package->seller_id === $this->sellerId($request), 404);

        if ($package->cards()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف باقة مرتبطة ببطاقات.');
        }

        $package->delete();

        return redirect()->route('seller.packages.index')
            ->with('success', 'تم حذف الباقة.');
    }
}
