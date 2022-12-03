<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\PhotoApiController;
use App\Http\Controllers\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register',[ApiAuthController::class,'register'])->name('auth.register');
Route::post('/login',[ApiAuthController::class,'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[ApiAuthController::class,'logout'])->name('auth.logout');
    Route::get('/getTokens',[ApiAuthController::class,'get_tokens'])->name('auth.getTokens');
    
    Route::apiResource('products',ProductApiController::class);
    Route::apiResource('photos',PhotoApiController::class);
});