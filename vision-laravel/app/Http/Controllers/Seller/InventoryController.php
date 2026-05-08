<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Package;
use App\Actions\Card\GenerateCardsAction;
use App\Actions\Card\ImportCardsFromCsvAction;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        $query = Card::whereHas('package', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with('package');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        $cards = $query->latest()->paginate(50);

        $stats = [
            'total' => $cards->total(),
            'available' => Card::whereHas('package', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->where('status', 'active')->count(),
            'sold' => Card::whereHas('package', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->where('status', 'sold')->count(),
            'reserved' => Card::whereHas('package', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->where('status', 'reserved')->count(),
        ];

        $packages = Package::where('seller_id', $seller->id)->get();

        return view('seller.inventory.index', compact('cards', 'stats', 'packages'));
    }

    public function create()
    {
        $seller = request()->user()->seller;
        $packages = Package::where('seller_id', $seller->id)->get();
        
        return view('seller.inventory.create', compact('packages'));
    }

    public function store(Request $request, GenerateCardsAction $action)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer|min:1|max:100',
            'price' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:today',
        ]);

        // Verify package belongs to seller
        $package = Package::where('seller_id', $seller->id)->findOrFail($validated['package_id']);

        try {
            $cards = $action->execute(
                $package,
                $validated['quantity'],
                [
                    'price' => $validated['price'] ?? null,
                    'expires_at' => $validated['expires_at'] ?? null,
                ]
            );

            return redirect()->route('seller.inventory.index')
                ->with('success', "Generated {$validated['quantity']} cards successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function importCsv(Request $request, ImportCardsFromCsvAction $importCardsFromCsvAction)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'file' => ['required', 'file', 'max:4096', 'mimes:csv,txt'],
        ]);

        $package = Package::query()->where('seller_id', $seller->id)->findOrFail($validated['package_id']);

        $body = (string) file_get_contents($validated['file']->getRealPath());
        $result = $importCardsFromCsvAction->execute($package, $body);

        $message = "Imported {$result['created']} voucher row(s).";
        if ($result['errors'] !== []) {
            $message .= ' '.count($result['errors']).' row(s) skipped.';
        }

        return redirect()->route('seller.inventory.index')
            ->with($result['errors'] === [] ? 'success' : 'warning', $message)
            ->with('import_errors', $result['errors']);
    }

    public function updateStatus(Card $card, Request $request)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'status' => 'required|in:active,sold,reserved,disabled',
        ]);

        $card->update($validated);

        return redirect()->back()->with('success', 'Card status updated');
    }

    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);

        if ($card->status !== 'active') {
            return redirect()->back()->with('error', 'Can only delete active cards');
        }

        $card->delete();

        return redirect()->back()->with('success', 'Card deleted');
    }
}
