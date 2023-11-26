<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Middleware\ApiTokenCheck;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API routes
Route::post('/v1/token', [Api::class , 'authenticate']);
Route::post('/v1/transactions', [Api::class , 'transactions'])->middleware(ApiTokenCheck::class);
Route::post('/v1/transactionbytrace', [Api::class , 'transactionByTrace'])->middleware(ApiTokenCheck::class);

