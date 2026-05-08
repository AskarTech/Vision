<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterSellerOnboardingAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register-seller');
    }

    public function store(Request $request, RegisterSellerOnboardingAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:160'],
            'business_phone' => ['required', 'string', 'max:20'],
            'manager_name' => ['required', 'string', 'max:255'],
            'manager_phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'network_name' => ['required', 'string', 'max:160'],
            'network_provider_code' => ['nullable', 'string', 'max:60'],
            'wallet_display_label' => ['nullable', 'string', 'max:120'],
        ]);

        $user = $action->execute([
            'business_name' => $validated['business_name'],
            'business_phone' => $validated['business_phone'],
            'manager_name' => $validated['manager_name'],
            'manager_phone' => $validated['manager_phone'],
            'password' => $validated['password'],
            'network_name' => $validated['network_name'],
            'network_provider_code' => $validated['network_provider_code'] ?? null,
            'wallet_display_label' => $validated['wallet_display_label'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('seller.dashboard')
            ->with('success', 'تم إنشاء حساب الشريك وشبكتك الأولى. مرحباً بك في لوحة التحكم.');
    }
}
