<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::get('/search/{query}', 'search');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
