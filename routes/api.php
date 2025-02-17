<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;


Route::prefix('admin')->group(function () {
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);

});
