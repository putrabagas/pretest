<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::fallback(function () {
    return response()->json([
        'code' => 401,
        'errors' => [
            'message' => 'Please login before access this menu'
            ]
    ], 401);
});

//--public api
    //--auth process
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    //--view and detail product
    Route::apiResource('/products', ProductController::class)->only([
        'index', 'show'
    ]);
    //--search products
    Route::post('/products/search', [ProductController::class, 'search']);

//--must authenticate api
Route::middleware('auth:api')->group(function(){
    //--auth process
    Route::post('/logout', [AuthController::class, 'logout']);
    //--add, update, delete products [admin only]
    Route::apiResource('/products', ProductController::class)->except([
        'index', 'show', 
    ])->middleware('admin');
    //--CRUD carts
    Route::apiResource('/carts', CartController::class)->except([
        'show',
    ]);

    //--purchase summary before checkout
    Route::get('/checkout', [OrderController::class, 'checkout']);
    //--create order
    Route::post('/checkout', [OrderController::class, 'createOrder']);
    //--get all orders based on who is logged in (if admin gets all order data)
    Route::get('/orders', [OrderController::class, 'getOrders']);
    //--payment
    Route::post('/orders/payment/{id}', [OrderController::class, 'payment']);
});