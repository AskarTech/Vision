<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use App\Models\User;
use App\Models\Seller;
use App\Models\TopupRequest;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth());
        $dateTo = $request->input('date_to', now()->endOfMonth());

        // Revenue metrics
        $totalRevenue = CardOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'paid')
            ->sum('total_amount');

        $totalTopups = TopupRequest::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawals = Withdrawal::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->sum('amount');

        // User metrics
        $newCustomers = User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('role', 'customer')
            ->count();

        $newSellers = Seller::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // Order metrics
        $totalOrders = CardOrder::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $completedOrders = CardOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'paid')
            ->count();

        $stats = [
            'revenue' => $totalRevenue,
            'topups' => $totalTopups,
            'withdrawals' => $totalWithdrawals,
            'new_customers' => $newCustomers,
            'new_sellers' => $newSellers,
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'conversion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0,
        ];

        // Daily revenue chart data
        $dailyRevenue = CardOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact('stats', 'dailyRevenue', 'dateFrom', 'dateTo'));
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth());
        $dateTo = $request->input('date_to', now()->endOfMonth());

        $salesBySeller = Seller::withCount(['cards as cards_sold' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('updated_at', [$dateFrom, $dateTo])
                  ->where('status', 'sold');
        }])
        ->withSum(['cards as revenue' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('updated_at', [$dateFrom, $dateTo])
                  ->where('status', 'sold');
        }], 'price')
        ->orderByDesc('cards_sold')
        ->get();

        return view('admin.reports.sales', compact('salesBySeller', 'dateFrom', 'dateTo'));
    }

    public function customers(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth());
        $dateTo = $request->input('date_to', now()->endOfMonth());

        $topCustomers = User::where('role', 'customer')
            ->withCount(['cardOrders as total_orders' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withSum(['cardOrders as total_spent' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }], 'total_amount')
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();

        return view('admin.reports.customers', compact('topCustomers', 'dateFrom', 'dateTo'));
    }
}
