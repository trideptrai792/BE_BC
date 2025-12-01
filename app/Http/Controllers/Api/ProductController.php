<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category_slug = $request->input('category');

        if ($category_slug) {
            $category = \App\Models\Category::where('slug', $category_slug)->firstOrFail();

            $products = Product::where('category_id', $category->id)
                ->where('status', 1)
                ->latest()
                ->paginate(24);

            return ProductResource::collection($products);
        }

        $products = Product::where('status', 1)
            ->latest()
            ->paginate(24);

        return ProductResource::collection($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status'      => ['required', 'integer'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status'      => ['required', 'integer'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

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
