<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RedirectAfterLoginAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, RedirectAfterLoginAction $redirectAfterLogin): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $identifier = trim($validated['identifier']);

        $user = User::query()
            ->where('status', 'active')
            ->where(function ($query) use ($identifier): void {
                $query->where('email', $identifier);

                if (! str_contains($identifier, '@')) {
                    $digitsOnly = preg_replace('/\D+/', '', $identifier) ?? '';
                    $query->orWhere('phone', $identifier);
                    if ($digitsOnly !== '' && $digitsOnly !== $identifier) {
                        $query->orWhere('phone', $digitsOnly);
                    }
                }
            })
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => [__('auth.failed')],
            ]);
        }

        Auth::login($user, (bool) ($validated['remember'] ?? false));
        $request->session()->regenerate();

        return redirect()->intended($redirectAfterLogin->execute($user));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
