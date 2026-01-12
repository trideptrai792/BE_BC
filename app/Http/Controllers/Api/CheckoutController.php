<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    private function activeCart(int $userId): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['shipping_fee' => 0]
        );
    }

    private function productWithStock(int $productId): Product
    {
        return Product::query()
            ->withSum(['stores' => fn($q) => $q->where('status', 1)], 'qty')
            ->findOrFail($productId);
    }

    private function unitPrice(Product $p): int
    {
        $base = (int)($p->price_buy ?? 0);
        $final = (int)($p->final_price ?? 0);
        return $final > 0 ? $final : $base;
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string'],
            'email' => ['required','email'],
            'phone' => ['required','string'],
            'address' => ['required','string'],
            'note' => ['nullable','string'],
            'payment_method' => ['required','in:cod,vnpay'],
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $cart = $this->activeCart($user->id);
        $items = CartItem::query()->where('cart_id', $cart->id)->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 422);
        }

        return DB::transaction(function () use ($data, $user, $cart, $items, $request) {
            $subtotal = 0;

            foreach ($items as $it) {
                $p = $this->productWithStock((int)$it->product_id);
                $stock = (int)($p->stores_sum_qty ?? 0);

                if ($stock > 0 && (int)$it->qty > $stock) {
                    throw ValidationException::withMessages([
                        'cart' => ["Sản phẩm vượt tồn kho (product_id={$p->id})"]
                    ]);
                }

                $unit = $this->unitPrice($p);
                $subtotal += $unit * (int)$it->qty;
            }

            $discount = 0;
            $shippingFee = (int)($cart->shipping_fee ?? 0);
            $total = max(0, $subtotal - $discount) + $shippingFee;

            $orderData = [
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
                'status' => 1,
                'created_by' => $user->id,

                // Nếu bạn đã add cột mới cho orders thì bật các field này
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
            ];

            if ($data['payment_method'] === 'cod') {
                $orderData['payment_method'] = 'cod';
                $orderData['payment_status'] = 'unpaid';
            } else {
                $orderData['payment_method'] = 'vnpay';
                $orderData['payment_status'] = 'pending';
            }

            $order = Order::create($orderData);

            foreach ($items as $it) {
                $p = $this->productWithStock((int)$it->product_id);
                $unit = $this->unitPrice($p);
                $qty = (int)$it->qty;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'price' => $unit,
                    'qty' => $qty,
                    'amount' => $unit * $qty,
                    'discount' => 0,
                ]);
            }

            if ($data['payment_method'] === 'cod') {
                $cart->status = 'checked_out';
                $cart->save();
                CartItem::query()->where('cart_id', $cart->id)->delete();

                return response()->json([
                    'order_id' => $order->id,
                    'payment_method' => 'cod',
                    'total' => $total,
                ]);
            }

            // vnpay: tạo txn_ref mới để tránh trùng / retry
            $txnRef = $order->id . '-' . time();

            $order->payment_ref = $txnRef;
            $order->save();

            // Không xóa cart tại đây (để retry nếu fail / hết hạn)
            $payUrl = app(VNPayService::class)->createPaymentUrl([
                'txn_ref' => $txnRef,
                'amount_vnd' => (int)$total,
                'order_info' => 'Thanh toan don hang: ' . $order->id,
                'ip' => $request->ip() ?: '127.0.0.1',
            ]);

            return response()->json([
                'order_id' => $order->id,
                'payment_method' => 'vnpay',
                'total' => $total,
                'payment_url' => $payUrl,
                'txn_ref' => $txnRef,
            ]);
        });
    }
}
