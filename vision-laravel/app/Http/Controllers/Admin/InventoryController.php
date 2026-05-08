<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Network;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->filteredCardQuery($request)->with(['package', 'network', 'seller']);

        $cards = $query->paginate(20);

        $stats = [
            'total' => Card::count(),
            'available' => Card::where('status', 'active')->count(),
            'reserved' => Card::where('status', 'reserved')->count(),
            'sold' => Card::where('status', 'sold')->count(),
            'reported' => Card::where('status', 'reported')->count(),
        ];

        $sellers = Seller::query()->orderBy('name')->get(['id', 'name']);
        $networks = Network::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.inventory.index', compact('cards', 'stats', 'sellers', 'networks'));
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
            'status' => 'required|in:active,reserved,sold,reported,refunded,disabled',
        ]);

        $card->update($validated);

        return redirect()->back()->with('success', __('admin.card_status_updated'));
    }

    public function bulkUpdate(Request $request)
    {
        $this->authorize('updateBulk', Card::class);

        $validated = $request->validate([
            'card_ids' => 'required|array',
            'card_ids.*' => 'exists:cards,id',
            'status' => 'required|in:active,reserved,sold,reported,refunded,disabled',
        ]);

        DB::transaction(function () use ($validated): void {
            Card::query()->whereIn('id', $validated['card_ids'])
                ->update(['status' => $validated['status']]);
        });

        return redirect()->back()->with('success', __('admin.cards_bulk_updated'));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('export', Card::class);

        $filename = 'inventory-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'id',
                'code',
                'serial_number',
                'status',
                'price',
                'package_id',
                'network_id',
                'seller_id',
                'created_at',
            ]);

            $this->filteredCardQuery($request)
                ->orderBy('id')
                ->chunk(500, function ($cards) use ($handle): void {
                    foreach ($cards as $card) {
                        fputcsv($handle, [
                            $card->id,
                            $card->code,
                            $card->serial_number ?? '',
                            $card->status,
                            $card->price,
                            $card->package_id,
                            $card->network_id,
                            $card->seller_id,
                            $card->created_at?->toIso8601String(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<Card>
     */
    private function filteredCardQuery(Request $request)
    {
        $query = Card::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->whereHas('package', function ($q) use ($request): void {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('network_id')) {
            $query->where('network_id', $request->network_id);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        return $query;
    }
}
