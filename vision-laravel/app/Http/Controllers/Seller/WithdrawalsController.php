<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Bank;
use Illuminate\Http\Request;

class WithdrawalsController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        $query = Withdrawal::where('seller_id', $seller->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->latest()->paginate(20);

        $stats = [
            'pending' => Withdrawal::where('seller_id', $seller->id)->where('status', 'pending')->count(),
            'approved' => Withdrawal::where('seller_id', $seller->id)->where('status', 'approved')->count(),
            'total_approved' => Withdrawal::where('seller_id', $seller->id)->where('status', 'approved')->sum('amount'),
            'pending_amount' => Withdrawal::where('seller_id', $seller->id)->where('status', 'pending')->sum('amount'),
        ];

        return view('seller.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function create()
    {
        $seller = request()->user()->seller;
        $banks = Bank::all();
        
        return view('seller.withdrawals.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_id' => 'required|exists:banks,id',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
        ]);

        // Check wallet balance
        if ($seller->wallet->balance < $validated['amount']) {
            return redirect()->back()->with('error', 'Insufficient wallet balance');
        }

        // Check for pending withdrawals
        $pendingCount = Withdrawal::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingCount >= 3) {
            return redirect()->back()->with('error', 'You have too many pending withdrawal requests');
        }

        Withdrawal::create([
            'seller_id' => $seller->id,
            'amount' => $validated['amount'],
            'bank_id' => $validated['bank_id'],
            'bank_name' => Bank::find($validated['bank_id'])->name,
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully');
    }

    public function show(Withdrawal $withdrawal)
    {
        $seller = request()->user()->seller;
        
        if ($withdrawal->seller_id !== $seller->id) {
            abort(403);
        }

        return view('seller.withdrawals.show', compact('withdrawal'));
    }
}
