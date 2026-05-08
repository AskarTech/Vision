<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('wallet')->where('role', 'customer')->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->paginate(20);

        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('status', 'active')->count(),
            'suspended' => User::where('role', 'customer')->where('status', 'suspended')->count(),
            'today' => User::where('role', 'customer')->whereDate('created_at', today())->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $this->authorize('view', $customer);

        $customer->load(['wallet', 'walletTransactions', 'topupRequests', 'cardOrders']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        $this->authorize('update', $customer);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended,banned',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully');
    }

    public function suspend(User $customer)
    {
        $this->authorize('suspend', $customer);

        $customer->update(['status' => 'suspended']);

        return redirect()->back()->with('success', 'Customer suspended successfully');
    }

    public function activate(User $customer)
    {
        $this->authorize('activate', $customer);

        $customer->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Customer activated successfully');
    }
}
