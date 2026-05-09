<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'orders' => CardOrder::query()->where('user_id', $user->id)->count(),
            'paid_orders' => CardOrder::query()->where('user_id', $user->id)->where('status', 'paid')->count(),
            'wallet_balance' => (float) ($user->wallet?->balance ?? 0),
            'points_balance' => (int) ($user->wallet?->points_balance ?? 0),
            'spent_total' => (float) CardOrder::query()->where('user_id', $user->id)->where('status', 'paid')->sum('total_amount'),
        ];

        $recentOrders = CardOrder::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }
}
