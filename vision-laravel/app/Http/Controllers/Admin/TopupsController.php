<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use App\Actions\Topup\ApproveTopupAction;
use App\Actions\Topup\RejectTopupAction;
use Illuminate\Http\Request;

class TopupsController extends Controller
{
    public function index(Request $request)
    {
        $query = TopupRequest::with('user')->latest();

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

        $stats = [
            'pending' => TopupRequest::where('status', 'pending')->count(),
            'today' => TopupRequest::whereDate('created_at', today())->sum('amount'),
            'total' => TopupRequest::where('status', 'approved')->sum('amount'),
            'approved' => TopupRequest::where('status', 'approved')->count(),
        ];

        return view('admin.topups.index', compact('topups', 'stats'));
    }

    public function approve(TopupRequest $topup, ApproveTopupAction $action)
    {
        $this->authorize('approve', $topup);

        try {
            $action->execute($topup, auth()->user());
            return redirect()->back()->with('success', __('admin.topup_approved'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(TopupRequest $topup, Request $request, RejectTopupAction $action)
    {
        $this->authorize('reject', $topup);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $action->execute($topup, $request->user(), $request->reason);
            return redirect()->back()->with('success', __('admin.topup_rejected'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
