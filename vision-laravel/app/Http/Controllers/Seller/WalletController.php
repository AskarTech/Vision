<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403);

        $wallet = $seller->wallet;

        $transactions = $wallet
            ? $wallet->transactions()->latest()->limit(25)->get()
            : collect();

        $grossSold = (float) Card::query()
            ->where('seller_id', $seller->id)
            ->where('status', 'sold')
            ->sum('price');

        $commissionRate = (float) ($seller->commission_rate ?? 0);
        $commissionRate = max(0.0, min(100.0, $commissionRate));
        $estimatedNetAfterCommission = round($grossSold * (100 - $commissionRate) / 100, 2);

        return view('seller.wallet.index', [
            'sellerWallet' => $wallet,
            'sellerTransactions' => $transactions,
            'grossSoldTotal' => $grossSold,
            'commissionRate' => $commissionRate,
            'estimatedNetAfterCommission' => $estimatedNetAfterCommission,
        ]);
    }
}
