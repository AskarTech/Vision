<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Checkout\CheckoutWithWalletAction;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Network;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function __construct(
        private readonly CheckoutWithWalletAction $checkoutWithWalletAction
    ) {}

    public function index(Request $request): View
    {
        $query = Package::query()
            ->with(['network', 'seller'])
            ->withCount(['cards as active_stock_count' => fn ($q) => $q->where('status', 'active')])
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
        $package->loadCount(['cards as active_stock_count' => fn ($q) => $q->where('status', 'active')]);

        $relatedPackages = Package::query()
            ->where('status', 'active')
            ->where('network_id', $package->network_id)
            ->whereKeyNot($package->id)
            ->withCount(['cards as active_stock_count' => fn ($q) => $q->where('status', 'active')])
            ->take(6)
            ->get();

        $inStock = ((int) ($package->active_stock_count ?? 0)) > 0;

        return view('customer.marketplace.show', compact('package', 'relatedPackages', 'inStock'));
    }

    public function buy(Request $request, Package $package): RedirectResponse
    {
        abort_unless($package->status === 'active', 404);

        $stock = Card::query()
            ->where('package_id', $package->id)
            ->where('status', 'active')
            ->count();

        if ($stock < 1) {
            return back()->with('error', 'هذه الباقة غير متوفرة حالياً (نفد المخزون).');
        }

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
