<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::post('customers', [CustomerController::class, 'store']);
Route::post('/customers/find-or-create', [CustomerController::class, 'findOrCreate']);
Route::get('/customers/lookup', [CustomerController::class, 'lookup']);
//api order
Route::post('orders', [OrderController::class, 'checkout']);

