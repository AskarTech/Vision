<?php

namespace App\Http\Middleware;

use App\Models\Seller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerManagerLinkedToSeller
{
    /**
     * Seller routes assume `users.seller_id` points at an existing sellers row.
     * Without it, controllers and policies return opaque 403 / errors.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || $user->role !== 'seller_manager') {
            return $next($request);
        }

        $sellerId = $user->seller_id;
        if ($sellerId === null || $sellerId === 0) {
            return response()->view('seller.account-incomplete', [], 403);
        }

        if (! Seller::query()->whereKey($sellerId)->exists()) {
            return response()->view('seller.account-incomplete', [], 403);
        }

        return $next($request);
    }
}
