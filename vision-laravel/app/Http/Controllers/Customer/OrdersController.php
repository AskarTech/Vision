<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index(Request $request): View
    {
        $query = CardOrder::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, CardOrder $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);
        $order->load(['items.card', 'items.package']);

        return view('customer.orders.show', compact('order'));
    }
}
