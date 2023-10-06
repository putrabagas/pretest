<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::fallback(function () {
    return response()->json([
        'code' => 401,
        'errors' => [
            'message' => 'Please login before access this menu'
            ]
    ], 401);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::apiResource('/products', ProductController::class)->only([
    'index', 'show'
]);

Route::middleware('auth:api')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('/products', ProductController::class)->except([
        'index', 'show', 
    ]);
    Route::apiResource('/carts', CartController::class)->except([
        'show',
    ]);
});