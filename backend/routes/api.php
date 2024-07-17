<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use \App\Http\Controllers\TagController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\ColorController;
use \App\Http\Controllers\SlideController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\BannerController;
use \App\Http\Controllers\ReviewController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\PromocodeController;


Route::middleware('authApi')->group(function () {
  Route::get('/tags', [TagController::class, 'index']);
  Route::get('/colors', [ColorController::class, 'index']);
  Route::get('/slides', [SlideController::class, 'index']);
  Route::get('/banners', [BannerController::class, 'index']);
  Route::get('/reviews', [ReviewController::class, 'index']);
  Route::get('/products', [ProductController::class, 'index']);
  Route::get('/categories', [CategoryController::class, 'index']);
  Route::get('/promocodes', [PromocodeController::class, 'index']);


  Route::get('/orders', [OrderController::class, 'index']);
  Route::post('/orders', [OrderController::class, 'store']);
  Route::post('/reviews', [ReviewController::class, 'store']);

  Route::get('/users', [User::class, function () {
    return User::all();
  }]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
