<?php

use Illuminate\Support\Facades\Route;

Route::get('/products', function () {
    return 'products';
});

Route::get('/store/sync', function () { return 'sync';});
