<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StoreSynchronizationController;
use Illuminate\Support\Facades\Route;

Route::get('/products/categories', [CategoriesController::class, 'index']);
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{product}', [ProductsController::class, 'show']);

Route::get('/statistics', StatisticsController::class);


Route::post('/store/sync', StoreSynchronizationController::class);
