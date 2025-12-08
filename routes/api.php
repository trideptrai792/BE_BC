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

/*
|--------------------------------------------------------------------------
| PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('products')->group(function () {
    // Client
    Route::get('/', [ProductController::class, 'index']);      // GET /api/products?category=slug
    Route::get('/{slug}', [ProductController::class, 'show']); // GET /api/products/{slug}

    // Admin (dùng id model binding)
    Route::post('/', [ProductController::class, 'store']);              // POST   /api/products
    Route::put('/{product}', [ProductController::class, 'update']);     // PUT    /api/products/{id}
    Route::delete('/{product}', [ProductController::class, 'destroy']); // DELETE /api/products/{id}
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
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/menus', [MenuController::class, 'adminIndex']);
    Route::post('/admin/menus', [MenuController::class, 'store']);
    Route::put('/admin/menus/{menu}', [MenuController::class, 'update']);
    Route::delete('/admin/menus/{menu}', [MenuController::class, 'destroy']);
});
