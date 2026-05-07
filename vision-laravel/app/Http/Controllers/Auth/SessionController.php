<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RuntimeException;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'customer', 'seller_manager'])],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::query()
            ->where('role', $validated['role'])
            ->where('status', 'active')
            ->where(function ($query) use ($validated): void {
                $query->where('phone', $validated['identifier'])
                    ->orWhere('email', $validated['identifier']);
            })
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw new RuntimeException('بيانات الدخول غير صحيحة.');
        }

        Auth::login($user, (bool) ($validated['remember'] ?? false));
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathForRole($user->role));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectPathForRole(string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'seller_manager' => route('seller.dashboard'),
            default => url('/'),
        };
    }
}
