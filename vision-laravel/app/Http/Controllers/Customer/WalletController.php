<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $wallet = $request->user()->wallet;
        $transactions = $wallet
            ? $wallet->transactions()->latest()->paginate(25)->withQueryString()
            : collect();

        return view('customer.wallet.index', compact('wallet', 'transactions'));
    }
}
