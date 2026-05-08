<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $request->user()->seller;

        $cardQuery = Card::query()->where('seller_id', $seller->id);

        $stats = [
            'sold' => (clone $cardQuery)->where('status', 'sold')->count(),
            'revenue' => (float) (clone $cardQuery)->where('status', 'sold')->sum('price'),
            'active' => (clone $cardQuery)->where('status', 'active')->count(),
            'reserved' => (clone $cardQuery)->where('status', 'reserved')->count(),
        ];

        return view('seller.sales.index', compact('stats'));
    }
}
