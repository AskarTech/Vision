<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardOrder;
use App\Models\Seller;
use App\Models\TopupRequest;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = $this->buildMetrics();

        $pendingTopups = TopupRequest::query()
            ->with(['user', 'wallet'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingWithdrawals = Withdrawal::query()
            ->with(['seller', 'requester'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = CardOrder::query()
            ->with(['user', 'walletTransaction'])
            ->latest()
            ->take(5)
            ->get();

        $recentAlerts = Card::query()
            ->whereIn('status', ['reported', 'disabled'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'pendingTopups' => $pendingTopups,
            'pendingWithdrawals' => $pendingWithdrawals,
            'recentOrders' => $recentOrders,
            'recentAlerts' => $recentAlerts,
        ]);
    }

    public function topups(): View
    {
        $metrics = $this->buildMetrics();

        $topups = TopupRequest::query()
            ->with(['user', 'wallet', 'paymentGateway', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('admin.topups.index', [
            'metrics' => $metrics,
            'topups' => $topups,
        ]);
    }

    /**
     * @return array{users:int,sellers:int,cards_active:int,cards_sold:int,topups_pending:int,withdrawals_pending:int,wallet_transactions:int,orders_today:int}
     */
    private function buildMetrics(): array
    {
        return [
            'users' => User::query()->count(),
            'sellers' => Seller::query()->count(),
            'cards_active' => Card::query()->where('status', 'active')->count(),
            'cards_sold' => Card::query()->where('status', 'sold')->count(),
            'topups_pending' => TopupRequest::query()->where('status', 'pending')->count(),
            'withdrawals_pending' => Withdrawal::query()->where('status', 'pending')->count(),
            'wallet_transactions' => WalletTransaction::query()->count(),
            'orders_today' => CardOrder::query()->whereDate('created_at', today())->count(),
        ];
    }
}
