<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\WalletTransaction::with(['user', 'cardOrder'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(50);

        $stats = [
            'total' => \App\Models\WalletTransaction::count(),
            'today' => \App\Models\WalletTransaction::whereDate('created_at', today())->count(),
            'total_amount' => \App\Models\WalletTransaction::sum('amount'),
            'by_type' => \App\Models\WalletTransaction::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
        ];

        return view('admin.audit.index', compact('transactions', 'stats'));
    }

    public function show(\App\Models\WalletTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['user', 'cardOrder']);

        return view('admin.audit.show', compact('transaction'));
    }

    public function topups(Request $request)
    {
        $query = \App\Models\TopupRequest::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $topups = $query->paginate(20);

        return view('admin.audit.topups', compact('topups'));
    }

    public function withdrawals(Request $request)
    {
        $query = \App\Models\Withdrawal::with('seller')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->paginate(20);

        return view('admin.audit.withdrawals', compact('withdrawals'));
    }

    public function orders(Request $request)
    {
        $query = \App\Models\CardOrder::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        return view('admin.audit.orders', compact('orders'));
    }
}
