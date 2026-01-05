<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function activeCart(int $userId): Cart {
        return Cart::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['shipping_fee' => 0]
        );
    }

    private function normVariant($variant): array {
        if (!is_array($variant)) return [];
        ksort($variant);
        return $variant;
    }

    private function variantKey(array $variant): string {
        if (!$variant) return '';
        return sha1(json_encode($variant, JSON_UNESCAPED_UNICODE));
    }

    private function productWithStock(int $productId): Product {
        return Product::query()
            ->withSum(['stores' => fn($q) => $q->where('status', 1)], 'qty')
            ->findOrFail($productId);
    }

    private function unitPrice(Product $p): int {
        $base = (int)($p->price_buy ?? 0);
        $final = (int)($p->final_price ?? 0);
        return $final > 0 ? $final : $base;
    }

    public function show(Request $request) {
        $user = $request->user();
        $cart = $this->activeCart($user->id);

        $items = CartItem::query()
            ->where('cart_id', $cart->id)
            ->with(['product'])
            ->get();

        $mapped = [];
        $subtotal = 0;

        foreach ($items as $it) {
            $p = $this->productWithStock($it->product_id);

            $unit = $this->unitPrice($p);
            $line = $unit * (int)$it->qty;
            $subtotal += $line;

            $mapped[] = [
                'id' => $it->id,
                'product_id' => $p->id,
                'name' => $p->name ?? '',
                'slug' => $p->slug ?? '',
                'image' => $p->thumbnail ?? null,
                'variant' => $it->variant_json ?? [],
                'qty' => (int)$it->qty,
                'unit_price' => $unit,
                'line_total' => $line,
                'stock' => (int)($p->stores_sum_qty ?? 0),
            ];
        }

        $discount = 0;
        if ($cart->coupon_code) {
            $discount = 0;
        }

        $shippingFee = (int)($cart->shipping_fee ?? 0);
        $total = max(0, $subtotal - $discount) + $shippingFee;

        return response()->json([
            'items' => $mapped,
            'summary' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
            ],
            'meta' => [
                'coupon_code' => $cart->coupon_code,
                'shipping_method' => $cart->shipping_method,
            ]
        ]);
    }

    public function addItem(Request $request) {
        $data = $request->validate([
            'product_id' => ['required','integer'],
            'qty' => ['required','integer','min:1'],
            'variant' => ['nullable','array'],
        ]);

        $user = $request->user();
        $cart = $this->activeCart($user->id);

        $variant = $this->normVariant($data['variant'] ?? []);
        $vKey = $this->variantKey($variant);

        $p = $this->productWithStock((int)$data['product_id']);
        $stock = (int)($p->stores_sum_qty ?? 0);

        return DB::transaction(function () use ($cart, $data, $variant, $vKey, $stock) {
            $item = CartItem::query()->firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => (int)$data['product_id'],
                'variant_key' => $vKey,
            ]);

            $cur = (int)($item->qty ?? 0);
            $next = $cur + (int)$data['qty'];

            if ($stock > 0 && $next > $stock) {
                $next = $stock;
            }

            $item->variant_json = $variant ?: null;
            $item->qty = max(1, $next);
            $item->save();

            return response()->json(['ok' => true, 'item_id' => $item->id]);
        });
    }

    public function updateItem(Request $request, int $id) {
        $data = $request->validate([
            'qty' => ['required','integer','min:0'],
        ]);

        $user = $request->user();
        $cart = $this->activeCart($user->id);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        if ((int)$data['qty'] === 0) {
            $item->delete();
            return response()->json(['ok' => true]);
        }

        $p = $this->productWithStock($item->product_id);
        $stock = (int)($p->stores_sum_qty ?? 0);

        $qty = (int)$data['qty'];
        if ($stock > 0 && $qty > $stock) $qty = $stock;

        $item->qty = max(1, $qty);
        $item->save();

        return response()->json(['ok' => true]);
    }

    public function removeItem(Request $request, int $id) {
        $user = $request->user();
        $cart = $this->activeCart($user->id);

        CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['ok' => true]);
    }

    public function clear(Request $request) {
        $user = $request->user();
        $cart = $this->activeCart($user->id);

        CartItem::query()->where('cart_id', $cart->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function updateCart(Request $request) {
        $data = $request->validate([
            'coupon_code' => ['nullable','string'],
            'shipping_method' => ['nullable','string'],
            'shipping_fee' => ['nullable','integer','min:0'],
        ]);

        $user = $request->user();
        $cart = $this->activeCart($user->id);

        $cart->coupon_code = $data['coupon_code'] ?? $cart->coupon_code;
        $cart->shipping_method = $data['shipping_method'] ?? $cart->shipping_method;
        if (array_key_exists('shipping_fee', $data)) {
            $cart->shipping_fee = (int)$data['shipping_fee'];
        }
        $cart->save();

        return response()->json(['ok' => true]);
    }
}



