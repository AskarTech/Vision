<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $query = WalletTransaction::query()->with(['user', 'cardOrder'])->latest();

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

        $transactions = $query->paginate(40);

        $stats = [
            'total' => WalletTransaction::query()->count(),
            'today' => WalletTransaction::query()->whereDate('created_at', today())->count(),
        ];

        return view('admin.activity.index', compact('transactions', 'stats'));
    }
}
