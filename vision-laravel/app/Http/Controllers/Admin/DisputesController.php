<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use App\Actions\Dispute\ResolveDisputeAction;
use Illuminate\Http\Request;

class DisputesController extends Controller
{
    public function index(Request $request)
    {
        $query = CardOrder::with(['customer', 'card.package'])->where('status', 'disputed');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $disputes = $query->latest()->paginate(20);

        $stats = [
            'pending' => CardOrder::where('status', 'disputed')->count(),
            'today' => CardOrder::where('status', 'disputed')->whereDate('created_at', today())->count(),
            'total_resolved' => CardOrder::whereIn('status', ['refunded', 'completed'])->whereNotNull('resolved_at')->count(),
            'refunded' => CardOrder::where('status', 'refunded')->count(),
        ];

        return view('admin.disputes.index', compact('disputes', 'stats'));
    }

    public function refund(CardOrder $order, Request $request, ResolveDisputeAction $action)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $action->refund($order, $request->reason);
            return redirect()->back()->with('success', 'Dispute resolved with refund');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(CardOrder $order, Request $request, ResolveDisputeAction $action)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $action->reject($order, $request->reason);
            return redirect()->back()->with('success', 'Dispute rejected, order marked as completed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
