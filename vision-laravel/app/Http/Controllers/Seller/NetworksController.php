<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NetworksController extends Controller
{
    private function sellerId(Request $request): int
    {
        $id = $request->user()?->seller_id;
        abort_unless($id, 403);

        return (int) $id;
    }

    public function index(Request $request): View
    {
        $sellerId = $this->sellerId($request);

        $networks = Network::query()
            ->where('seller_id', $sellerId)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Network::query()->where('seller_id', $sellerId)->count(),
            'active' => Network::query()->where('seller_id', $sellerId)->where('status', 'active')->count(),
        ];

        return view('seller.networks.index', compact('networks', 'stats'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Network::class);

        return view('seller.networks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Network::class);
        $sellerId = $this->sellerId($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('networks', 'slug')],
            'provider_code' => ['nullable', 'string', 'max:60'],
            'status' => ['required', 'in:active,disabled'],
        ]);

        $slugInput = trim((string) ($validated['slug'] ?? ''));
        $slug = $slugInput !== ''
            ? $slugInput
            : $this->makeUniqueSlug(Str::slug($validated['name']));

        Network::query()->create([
            'seller_id' => $sellerId,
            'name' => $validated['name'],
            'slug' => $slug,
            'provider_code' => $validated['provider_code'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('seller.networks.index')
            ->with('success', 'تم إنشاء الشبكة بنجاح.');
    }

    public function edit(Request $request, Network $network): View
    {
        $this->authorize('update', $network);
        abort_unless((int) $network->seller_id === $this->sellerId($request), 404);

        return view('seller.networks.edit', compact('network'));
    }

    public function update(Request $request, Network $network): RedirectResponse
    {
        $this->authorize('update', $network);
        abort_unless((int) $network->seller_id === $this->sellerId($request), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:100', Rule::unique('networks', 'slug')->ignore($network->id)],
            'provider_code' => ['nullable', 'string', 'max:60'],
            'status' => ['required', 'in:active,disabled'],
        ]);

        $network->update($validated);

        return redirect()->route('seller.networks.index')
            ->with('success', 'تم تحديث الشبكة.');
    }

    public function destroy(Request $request, Network $network): RedirectResponse
    {
        $this->authorize('delete', $network);
        abort_unless((int) $network->seller_id === $this->sellerId($request), 404);

        if ($network->cards()->count() > 0 || $network->packages()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف شبكة مرتبطة بباقات أو بطاقات.');
        }

        $network->delete();

        return redirect()->route('seller.networks.index')
            ->with('success', 'تم حذف الشبكة.');
    }

    private function makeUniqueSlug(string $base): string
    {
        $slug = $base !== '' ? $base : 'network-'.Str::lower(Str::random(6));

        while (Network::query()->where('slug', $slug)->exists()) {
            $slug = ($base !== '' ? $base : 'network').'-'.Str::lower(Str::random(4));
        }

        return $slug;
    }
}
