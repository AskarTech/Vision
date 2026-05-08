<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterCustomerAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, RegisterCustomerAction $registerCustomerAction): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $registerCustomerAction->execute([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.marketplace.index')
            ->with('success', 'تم إنشاء حسابك بنجاح. مرحباً بك في سوق البطاقات.');
    }
}
