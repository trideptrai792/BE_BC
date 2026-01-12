<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FlashSaleController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ProductStoreController;
use App\Http\Controllers\Api\ProductSaleController;
use App\Http\Controllers\Api\ProductStoreLogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AdminCartController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\VNPayController;
use App\Models\Attribute;
use App\Models\ProductAttribute;

/*
  NOTE:
  - Tất cả route trong file này tự động có prefix "/api"
    => Route::post('/auth/login'...) => URL thật: /api/auth/login
*/

/* ===================== AUTH (JWT) ===================== */
// /api/auth/login
Route::post('/auth/login', [AuthController::class, 'login']);

// /api/auth/register (2 đường dẫn, giữ theo code bạn)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']);

// /api/auth/me, /api/auth/logout (cần JWT)
Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});


/* ===================== PRODUCTS ===================== */
// prefix('products') => tất cả URL bắt đầu bằng /api/products/...
Route::prefix('products')->group(function () {
    Route::get('/new', [ProductController::class, 'productNew']); // /api/products/new
    Route::get('/', [ProductController::class, 'index']);         // /api/products
    Route::get('/{slug}', [ProductController::class, 'show']);    // /api/products/{slug}

    // Admin CRUD (hiện chưa bọc auth, nếu muốn thì đưa vào group admin ở dưới)
    Route::post('/', [ProductController::class, 'store']);              // /api/products
    Route::put('/{product}', [ProductController::class, 'update']);     // /api/products/{id}
    Route::delete('/{product}', [ProductController::class, 'destroy']); // /api/products/{id}
});


/* ===================== CATEGORIES ===================== */
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);       // /api/categories
    Route::get('/{slug}', [CategoryController::class, 'show']);  // /api/categories/{slug}

    // Admin CRUD (hiện chưa bọc auth)
    Route::post('/', [CategoryController::class, 'store']);               // /api/categories
    Route::put('/{category}', [CategoryController::class, 'update']);     // /api/categories/{id}
    Route::delete('/{category}', [CategoryController::class, 'destroy']); // /api/categories/{id}
});


/* ===================== UPLOAD IMAGE ===================== */
Route::post('/upload-image', [ProductImageController::class, 'uploadImage']); // /api/upload-image


/* ===================== POSTS ===================== */
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);        // /api/posts
    Route::get('/{slug}', [PostController::class, 'show']);   // /api/posts/{slug}

    // Admin CRUD (hiện chưa bọc auth)
    Route::post('/', [PostController::class, 'store']);            // /api/posts
    Route::put('/{post}', [PostController::class, 'update']);      // /api/posts/{id}
    Route::delete('/{post}', [PostController::class, 'destroy']);  // /api/posts/{id}
});


/* ===================== FLASH SALE ===================== */
Route::get('/flash-sale', [FlashSaleController::class, 'index']); // /api/flash-sale


/* ===================== GOOGLE AUTH ===================== */
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']); // /api/auth/google/redirect
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']); // /api/auth/google/callback


/* ===================== MENUS (CLIENT) ===================== */
Route::get('/menus', [MenuController::class, 'index']); // /api/menus


/* ===================== ATTRIBUTES ===================== */
Route::get('/attributes', function () {
    return Attribute::with('values')->get();
}); // /api/attributes


/* ===================== VARIANTS / PRODUCT ATTRIBUTES ===================== */
Route::post('/product-variants', [ProductVariantController::class, 'store']);                    // /api/product-variants
Route::post('/product-variants/{variant}/values', [ProductVariantController::class, 'addValue']); // /api/product-variants/{id}/values

Route::post('/product-attributes', function (Request $r) {
    $data = $r->validate([
        'product_id'   => 'required|exists:products,id',
        'attribute_id' => 'required|exists:attributes,id',
        'value'        => 'required|string|max:255',
    ]);
    $pa = ProductAttribute::create($data);
    return response()->json($pa, 201);
}); // /api/product-attributes


/* ===================== CART (CLIENT, cần JWT) ===================== */
Route::middleware('auth:api')->group(function () {
    Route::get('/cart', [CartController::class, 'show']);                 // /api/cart
    Route::post('/cart/items', [CartController::class, 'addItem']);       // /api/cart/items
    Route::patch('/cart/items/{id}', [CartController::class, 'updateItem']); // /api/cart/items/{id}
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']); // /api/cart/items/{id}
    Route::delete('/cart/clear', [CartController::class, 'clear']);       // /api/cart/clear
    Route::patch('/cart', [CartController::class, 'updateCart']);         // /api/cart

    // Checkout (user login)
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
});


/* ===================== ADMIN (JWT + admin middleware) ===================== */
// prefix('admin') => tất cả URL bắt đầu bằng /api/admin/...
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    // Admin carts
    Route::get('/carts', [AdminCartController::class, 'index']);      // /api/admin/carts
    Route::get('/carts/{cart}', [AdminCartController::class, 'show']); // /api/admin/carts/{id}

    // Admin users
    Route::get('/users', [AdminUserController::class, 'index']);        // /api/admin/users
    Route::get('/users/{id}', [AdminUserController::class, 'show']);    // /api/admin/users/{id}
    Route::put('/users/{id}', [AdminUserController::class, 'update']);  // /api/admin/users/{id}
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']); // /api/admin/users/{id}

    // Admin flash sales
    Route::get('/flash-sales', [FlashSaleController::class, 'adminIndex']);     // /api/admin/flash-sales
    Route::post('/flash-sales', [FlashSaleController::class, 'store']);         // /api/admin/flash-sales
    Route::put('/flash-sales/{flashSale}', [FlashSaleController::class, 'update']); // /api/admin/flash-sales/{id}
    Route::delete('/flash-sales/{flashSale}', [FlashSaleController::class, 'destroy']); // /api/admin/flash-sales/{id}

    // Admin menus
    Route::get('/menus', [MenuController::class, 'adminIndex']);          // /api/admin/menus
    Route::post('/menus', [MenuController::class, 'store']);              // /api/admin/menus
    Route::get('/menus/{menu}', [MenuController::class, 'show']);         // /api/admin/menus/{id}
    Route::put('/menus/{menu}', [MenuController::class, 'update']);       // /api/admin/menus/{id}
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy']);   // /api/admin/menus/{id}

    // Admin product stores
    Route::get('/product-stores', [ProductStoreController::class, 'index']);      // /api/admin/product-stores
    Route::post('/product-stores', [ProductStoreController::class, 'store']);     // /api/admin/product-stores
    Route::put('/product-stores/{productStore}', [ProductStoreController::class, 'update']); // /api/admin/product-stores/{id}
    Route::delete('/product-stores/{productStore}', [ProductStoreController::class, 'destroy']); // /api/admin/product-stores/{id}

    // Admin product sales
    Route::get('/product-sales', [ProductSaleController::class, 'index']);        // /api/admin/product-sales
    Route::post('/product-sales', [ProductSaleController::class, 'store']);       // /api/admin/product-sales
    Route::put('/product-sales/{productSale}', [ProductSaleController::class, 'update']); // /api/admin/product-sales/{id}
    Route::delete('/product-sales/{productSale}', [ProductSaleController::class, 'destroy']); // /api/admin/product-sales/{id}

    // Admin product store logs
    Route::get('/product-store-logs', [ProductStoreLogController::class, 'index']); // /api/admin/product-store-logs
});

// VNPay callbacks (public)
Route::get('/vnpay/return', [VNPayController::class, 'returnUrl']);
Route::get('/vnpay/ipn', [VNPayController::class, 'ipn']);
