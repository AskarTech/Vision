<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = CardOrder::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_channel')) {
            $query->where('payment_channel', $request->payment_channel);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        $stats = [
            'total' => CardOrder::count(),
            'pending' => CardOrder::where('status', 'pending')->count(),
            'completed' => CardOrder::where('status', 'completed')->count(),
            'cancelled' => CardOrder::where('status', 'cancelled')->count(),
            'revenue' => CardOrder::where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(CardOrder $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'walletTransaction', 'items.card']);

        return view('admin.orders.show', compact('order'));
    }

    public function cancel(CardOrder $order)
    {
        $this->authorize('cancel', $order);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Order cancelled successfully');
    }

    public function refund(CardOrder $order)
    {
        $this->authorize('refund', $order);

        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Only completed orders can be refunded');
        }

        // TODO: Implement refund logic with wallet transaction reversal
        $order->update(['status' => 'refunded']);

        return redirect()->back()->with('success', 'Order refunded successfully');
    }
}
