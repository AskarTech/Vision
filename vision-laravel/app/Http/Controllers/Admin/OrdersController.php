<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\RefundPaidOrderAction;
use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use Illuminate\Http\Request;
use Throwable;

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
            'completed' => CardOrder::where('status', 'paid')->count(),
            'cancelled' => CardOrder::where('status', 'cancelled')->count(),
            'revenue' => CardOrder::where('status', 'paid')->sum('total_amount'),
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
            return redirect()->back()->with('error', __('admin.order_cancel_only_pending'));
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', __('admin.order_cancelled'));
    }

    public function refund(CardOrder $order, RefundPaidOrderAction $action)
    {
        $this->authorize('refund', $order);

        try {
            $action->execute($order);

            return redirect()->back()->with('success', __('admin.order_refunded'));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
