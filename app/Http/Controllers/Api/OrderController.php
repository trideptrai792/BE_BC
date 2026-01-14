<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = (int)($request->query('per_page', 20));
        $status = $request->query('status');
        $processedOnly = $request->boolean('processed', true);

        $query = Order::query()
            ->where('user_id', $user->id)
            ->withCount('details')
            ->orderByDesc('id');

        if ($status !== null && $status !== '') {
            $query->where('status', (int)$status);
        } elseif ($processedOnly) {
            $query->whereIn('status', [2, 3, 4, 5]);
        }

        return response()->json($query->paginate($perPage));
    }

    public function show(Request $request, Order $order)
    {
        if ((int)$order->user_id !== (int)$request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $order->load(['details.product']);

        $subtotal = (int)$order->details->sum('amount');
        $discount = (int)$order->details->sum('discount');
        $shippingFee = (int)($order->shipping_fee ?? 0);
        $total = max(0, $subtotal - $discount) + $shippingFee;

        return response()->json([
            'order' => $order,
            'summary' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
            ],
        ]);
    }
}
