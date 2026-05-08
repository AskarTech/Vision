<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Checkout\CheckoutWithWalletAction;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function __construct(
        private readonly CheckoutWithWalletAction $checkoutWithWalletAction
    ) {
    }

    public function index(Request $request): View
    {
        $query = Package::query()
            ->with(['network', 'seller'])
            ->where('status', 'active')
            ->whereHas('network', fn ($q) => $q->where('status', 'active'))
            ->latest();

        if ($request->filled('network_id')) {
            $query->where('network_id', $request->integer('network_id'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where(function ($scoped) use ($search): void {
                $scoped->where('name', 'like', "%{$search}%")
                    ->orWhereHas('network', fn ($networkQuery) => $networkQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $packages = $query->paginate(24)->withQueryString();
        $networks = Network::query()->where('status', 'active')->orderBy('name')->get();

        return view('customer.marketplace.index', compact('packages', 'networks'));
    }

    public function show(Package $package): View
    {
        abort_unless($package->status === 'active', 404);

        $package->load(['network', 'seller']);
        $relatedPackages = Package::query()
            ->where('status', 'active')
            ->where('network_id', $package->network_id)
            ->whereKeyNot($package->id)
            ->take(6)
            ->get();

        return view('customer.marketplace.show', compact('package', 'relatedPackages'));
    }

    public function buy(Request $request, Package $package): RedirectResponse
    {
        abort_unless($package->status === 'active', 404);

        try {
            $order = $this->checkoutWithWalletAction->execute(
                user: $request->user(),
                items: [['package_id' => $package->id, 'quantity' => 1]],
                idempotencyKey: (string) Str::uuid()
            );
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'تم تنفيذ عملية الشراء بنجاح.');
    }
}
