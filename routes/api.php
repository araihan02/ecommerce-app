<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController as ApiProductController; // Alias untuk user side
use App\Http\Controllers\Admin\ProductController as AdminProductController; // Nanti untuk admin
use App\Http\Controllers\Admin\AuthController as AdminAuthController; // Nanti untuk admin

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;


// Rute API untuk User Side (Publik)
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{product}', [ApiProductController::class, 'show']);

// Rute API untuk Admin Side akan ditambahkan di sini nanti
// Rute API untuk Admin Side
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Rute yang memerlukan autentikasi admin (Sanctum)
    Route::middleware(['auth:sanctum', 'admin'])->group(function () { // Tambahkan middleware 'admin'
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/user', [AdminAuthController::class, 'user']); // Untuk cek user login
        Route::apiResource('/products', AdminProductController::class);
        // apiResource mencakup: index, store, show, update, destroy
    });
});
