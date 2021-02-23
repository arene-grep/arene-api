<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TradingCardGameController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\OrderController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'products' => ProductController::class,
    'categories' => CategoryController::class,
    'tcgames' => TradingCardGameController::class,
    'languages' => LanguageController::class,
    'events' => EventController::class,
    'users' => UserController::class,
    'orders' => OrderController::class,
]);

