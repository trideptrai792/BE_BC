<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {   
        $category_slug = $request->input('category');

        $query = Product::query()
            ->where('status', 1);

        if ($category_slug) {
            $category = Category::where('slug', $category_slug)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $products = $query
            ->with([
                'category',
                'images',
                'productAttributes.attribute',
            ])
            ->latest()
            ->paginate(24);

        return ProductResource::collection($products);
    }

    public function show($slug)
    {
        $product = Product::with([
                'category',
                'images',
                'productAttributes.attribute',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }

    // ========== ADMIN: THÊM MỚI ==========
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255'],
            'price'       => ['required', 'numeric'],
            'thumbnail'   => ['nullable', 'string', 'max:255'],
            'content'     => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status'      => ['nullable', 'integer'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['price_buy'] = $data['price'];
        unset($data['price']);

        $product = Product::create($data);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    // ========== ADMIN: CẬP NHẬT ==========
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255'],
            'price'       => ['required', 'numeric'],
            'thumbnail'   => ['nullable', 'string', 'max:255'],
            'content'     => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status'      => ['nullable', 'integer'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['price_buy'] = $data['price'];
        unset($data['price']);

        $product->update($data);

        return new ProductResource($product->fresh());
    }

    // ========== ADMIN: XÓA ==========
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Xóa sản phẩm thành công',
        ]);
    }
}
