<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\CardOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index(Request $request): View
    {
        $sellerId = $request->user()?->seller_id;
        abort_unless($sellerId, 403);

        $query = CardOrder::query()
            ->whereHas('items.card', fn ($cardsQuery) => $cardsQuery->where('seller_id', $sellerId))
            ->with(['user', 'items'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('payment_channel')) {
            $query->where('payment_channel', $request->string('payment_channel'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where(function ($scopedQuery) use ($search) {
                $scopedQuery->where('id', $search)
                    ->orWhereHas('user', function ($usersQuery) use ($search) {
                        $usersQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $statsBase = CardOrder::query()
            ->whereHas('items.card', fn ($cardsQuery) => $cardsQuery->where('seller_id', $sellerId));

        $stats = [
            'total' => (clone $statsBase)->count(),
            'paid' => (clone $statsBase)->where('status', 'paid')->count(),
            'pending' => (clone $statsBase)->where('status', 'pending')->count(),
            'revenue' => (clone $statsBase)->where('status', 'paid')->sum('total_amount'),
        ];

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    public function show(Request $request, CardOrder $order): View
    {
        $sellerId = $request->user()?->seller_id;
        abort_unless($sellerId, 403);

        $isOwnedBySeller = $order->items()
            ->whereHas('card', fn ($cardsQuery) => $cardsQuery->where('seller_id', $sellerId))
            ->exists();

        abort_unless($isOwnedBySeller, 404);

        $order->load(['user', 'items.card', 'items.package']);

        return view('seller.orders.show', compact('order'));
    }
}
