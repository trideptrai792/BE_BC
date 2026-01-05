<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class AdminCartController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $carts = Cart::query()
            ->with([
                'user',
                'items.product',
            ])
            ->when($q, function ($qr) use ($q) {
                $qr->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(20);

        return response()->json($carts);
    }

    public function show(Cart $cart)
    {
        $cart->load(['user', 'items.product']);
        return response()->json($cart);
    }
}
