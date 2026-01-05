<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FlashSaleController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ProductStoreController;
use App\Models\Attribute;
use App\Http\Controllers\Api\ProductVariantController;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductSaleController;
use App\Http\Controllers\Api\ProductStoreLogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AdminCartController;
/*
|--------------------------------------------------------------------------
| PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('products')->group(function () {
       Route::get('/new', [ProductController::class, 'productNew']); 
    // Client
    Route::get('/', [ProductController::class, 'index']);      // GET /api/products?category=slug
    Route::get('/{slug}', [ProductController::class, 'show']); // GET /api/products/{slug}

    // Admin (dùng id model binding)
    Route::post('/', [ProductController::class, 'store']);              // POST   /api/products
    Route::put('/{product}', [ProductController::class, 'update']);     // PUT    /api/products/{id}
    Route::delete('/{product}', [ProductController::class, 'destroy']); 
    // DELETE /api/products/{id}
});

/*
|--------------------------------------------------------------------------
| CATEGORY ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('categories')->group(function () {
    // Client
    Route::get('/', [CategoryController::class, 'index']);      // GET /api/categories
    Route::get('/{slug}', [CategoryController::class, 'show']); // GET /api/categories/{slug}

    // Admin
    Route::post('/', [CategoryController::class, 'store']);               // POST   /api/categories
    Route::put('/{category}', [CategoryController::class, 'update']);     // PUT    /api/categories/{id}
    Route::delete('/{category}', [CategoryController::class, 'destroy']); // DELETE /api/categories/{id}
});

/*
|--------------------------------------------------------------------------
| AUTH (SANCTUM)
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Khi login/role xong thì có thể bảo vệ admin:
    // Route::post('/products', [ProductController::class, 'store']);
    // Route::put('/products/{product}', [ProductController::class, 'update']);
    // Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| UPLOAD IMAGE
|--------------------------------------------------------------------------
*/

Route::post('/upload-image', [ProductImageController::class, 'uploadImage']);



/////////////////pos
Route::prefix('posts')->group(function () {
    // Client
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{slug}', [PostController::class, 'show']);

    // Admin (nếu muốn bảo vệ bằng sanctum => bọc trong middleware auth:sanctum)
    Route::post('/', [PostController::class, 'store']);
    Route::put('/{post}', [PostController::class, 'update']);
    Route::delete('/{post}', [PostController::class, 'destroy']);
});




// fash sale

Route::get('/flash-sale', [FlashSaleController::class, 'index']);



Route::middleware('auth:sanctum')->group(function () {
    // CRUD flash sale cho ADMIN
    Route::get('/admin/flash-sales', [FlashSaleController::class, 'adminIndex']);
    Route::post('/admin/flash-sales', [FlashSaleController::class, 'store']);
    Route::put('/admin/flash-sales/{flashSale}', [FlashSaleController::class, 'update']);
    Route::delete('/admin/flash-sales/{flashSale}', [FlashSaleController::class, 'destroy']);
});



Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);



//xem user va dang ky 
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']);
// Admin xem danh sách user
Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin/users', [AdminUserController::class, 'index']);
    Route::get('admin/users/{id}', [AdminUserController::class, 'show']);
    Route::put('admin/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('admin/users/{id}', [AdminUserController::class, 'destroy']);
});




    // Client
Route::get('/menus', [MenuController::class, 'index']);

// Admin
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/carts', [AdminCartController::class, 'index']);
    Route::get('/carts/{cart}', [AdminCartController::class, 'show']);

    Route::get('/menus', [MenuController::class, 'adminIndex']);
    Route::post('/menus', [MenuController::class, 'store']);
    Route::get('/menus/{menu}', [MenuController::class, 'show']);
    Route::put('/menus/{menu}', [MenuController::class, 'update']);
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy']);

    Route::get('/product-stores', [ProductStoreController::class, 'index']);
    Route::post('/product-stores', [ProductStoreController::class, 'store']);
    Route::put('/product-stores/{productStore}', [ProductStoreController::class, 'update']);
    Route::delete('/product-stores/{productStore}', [ProductStoreController::class, 'destroy']);
});



Route::get('/attributes', function () {
    return Attribute::with('values')->get(); // trả kèm product_attribute_values
});



Route::post('/product-variants', [ProductVariantController::class, 'store']);
Route::post('/product-variants/{variant}/values', [ProductVariantController::class, 'addValue']);



Route::post('/product-attributes', function (Request $r) {
    $data = $r->validate([
        'product_id'   => 'required|exists:products,id',
        'attribute_id' => 'required|exists:attributes,id',
        'value'        => 'required|string|max:255',
    ]);
    $pa = ProductAttribute::create($data);
    return response()->json($pa, 201);
});



Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/product-sales', [ProductSaleController::class, 'index']);
    Route::post('/product-sales', [ProductSaleController::class, 'store']);
    Route::put('/product-sales/{productSale}', [ProductSaleController::class, 'update']);
    Route::delete('/product-sales/{productSale}', [ProductSaleController::class, 'destroy']);




});



Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/product-store-logs', [ProductStoreLogController::class, 'index']);
});


//gio hang 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::patch('/cart/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::patch('/cart', [CartController::class, 'updateCart']);
});


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/carts', [AdminCartController::class, 'index']);
    Route::get('/carts/{cart}', [AdminCartController::class, 'show']);
});