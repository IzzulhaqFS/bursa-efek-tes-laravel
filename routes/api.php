<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->group(function () {
    Route::get('/category-products', [App\Http\Controllers\Api\CategoryProductController::class, 'index'])->name('index');
    Route::post('/category-products', [App\Http\Controllers\Api\CategoryProductController::class, 'store'])->name('store');
    Route::get('/category-products/{id}', [App\Http\Controllers\Api\CategoryProductController::class, 'show'])->name('show');
    Route::put('/category-products/{id}', [App\Http\Controllers\Api\CategoryProductController::class, 'update'])->name('update');
    Route::delete('/category-products/{id}', [App\Http\Controllers\Api\CategoryProductController::class, 'destroy'])->name('destroy');

    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index'])->name('index');
    Route::post('/products', [App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
    Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show'])->name('show');
    Route::put('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');
    Route::delete('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('destroy');
});