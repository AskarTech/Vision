<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::with(['package', 'network', 'seller'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->whereHas('package', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('network_id')) {
            $query->where('network_id', $request->network_id);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $cards = $query->paginate(20);

        $stats = [
            'total' => Card::count(),
            'available' => Card::where('status', 'available')->count(),
            'reserved' => Card::where('status', 'reserved')->count(),
            'sold' => Card::where('status', 'sold')->count(),
            'activated' => Card::where('status', 'activated')->count(),
        ];

        return view('admin.inventory.index', compact('cards', 'stats'));
    }

    public function show(Card $card)
    {
        $this->authorize('view', $card);

        $card->load(['package', 'network', 'seller', 'orderItem.order']);

        return view('admin.inventory.show', compact('card'));
    }

    public function updateStatus(Request $request, Card $card)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'status' => 'required|in:available,reserved,sold,activated,expired,disabled',
        ]);

        $card->update($validated);

        return redirect()->back()->with('success', 'Card status updated successfully');
    }

    public function bulkUpdate(Request $request)
    {
        $this->authorize('updateBulk', Card::class);

        $validated = $request->validate([
            'card_ids' => 'required|array',
            'card_ids.*' => 'exists:cards,id',
            'status' => 'required|in:available,reserved,sold,activated,expired,disabled',
        ]);

        Card::whereIn('id', $validated['card_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Cards updated successfully');
    }

    public function export(Request $request)
    {
        $this->authorize('export', Card::class);

        // TODO: Implement CSV/Excel export
        return redirect()->back()->with('info', 'Export functionality coming soon');
    }
}
