<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Actions\Withdrawal\ApproveWithdrawalAction;
use App\Actions\Withdrawal\RejectWithdrawalAction;
use Illuminate\Http\Request;

class WithdrawalsController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with(['seller', 'requester'])->latest();

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

        $stats = [
            'pending' => Withdrawal::where('status', 'pending')->count(),
            'today' => Withdrawal::whereDate('created_at', today())->sum('amount'),
            'total' => Withdrawal::where('status', 'approved')->sum('amount'),
            'approved' => Withdrawal::where('status', 'approved')->count(),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function approve(Withdrawal $withdrawal, ApproveWithdrawalAction $action)
    {
        $this->authorize('approve', $withdrawal);

        try {
            $action->execute($withdrawal, auth()->user());
            return redirect()->back()->with('success', __('admin.withdrawal_approved'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(Withdrawal $withdrawal, Request $request, RejectWithdrawalAction $action)
    {
        $this->authorize('reject', $withdrawal);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $action->execute($withdrawal, $request->user(), $request->reason);
            return redirect()->back()->with('success', __('admin.withdrawal_rejected'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
