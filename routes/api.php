<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoreSynchronizationController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductsController::class, 'index']);

Route::post('/store/sync', StoreSynchronizationController::class);
