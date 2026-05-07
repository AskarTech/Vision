<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Network;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        // dd('Seller dashboard is under construction. Please check back later.');
        $user = Auth::user();
        $sellerId = $user?->seller_id;

        abort_unless($sellerId, 403);

        $seller = Seller::query()->withCount(['networks', 'packages', 'cards', 'banks', 'managers'])->findOrFail($sellerId);

        $metrics = [
            'networks_active' => Network::query()->where('seller_id', $sellerId)->where('status', 'active')->count(),
            'packages_active' => Package::query()->where('seller_id', $sellerId)->where('status', 'active')->count(),
            'cards_active' => Card::query()->where('seller_id', $sellerId)->where('status', 'active')->count(),
            'cards_reserved' => Card::query()->where('seller_id', $sellerId)->where('status', 'reserved')->count(),
            'cards_sold' => Card::query()->where('seller_id', $sellerId)->where('status', 'sold')->count(),
            'cards_reported' => Card::query()->where('seller_id', $sellerId)->where('status', 'reported')->count(),
        ];

        $recentNetworks = Network::query()
            ->where('seller_id', $sellerId)
            ->latest()
            ->take(5)
            ->get();

        $recentPackages = Package::query()
            ->where('seller_id', $sellerId)
            ->withCount('cards')
            ->latest()
            ->take(5)
            ->get();

        $recentCards = Card::query()
            ->where('seller_id', $sellerId)
            ->latest()
            ->take(8)
            ->get();

        $recentManagers = SellerManager::query()
            ->where('seller_id', $sellerId)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', [
            'seller' => $seller,
            'metrics' => $metrics,
            'recentNetworks' => $recentNetworks,
            'recentPackages' => $recentPackages,
            'recentCards' => $recentCards,
            'recentManagers' => $recentManagers,
        ]);
    }
}
