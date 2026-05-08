<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $seller = $user->seller;

        abort_unless($seller instanceof Seller, 403);

        return view('seller.settings.index', compact('user', 'seller'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('seller.settings.index')
            ->with('success', 'تم تحديث بيانات حسابك.');
    }

    public function updateBusiness(Request $request): RedirectResponse
    {
        $seller = $request->user()->seller;

        abort_unless($seller instanceof Seller, 403);

        $this->authorize('update', $seller);

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:160'],
            'business_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $seller->update([
            'name' => $validated['business_name'],
            'phone' => $validated['business_phone'] ?? $seller->phone,
        ]);

        return redirect()->route('seller.settings.index')
            ->with('success', 'تم تحديث بيانات النشاط التجاري.');
    }
}
