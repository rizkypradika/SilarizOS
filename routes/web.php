<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.auth.auth.login');
});

Route::get('/auth/login-redirect', function () {
    return redirect()->route('filament.auth.auth.login');
})->name('login');

Route::get('/checkout/{product}', function (\App\Models\Product $product) {
    return "Checkout page for: " . $product->name . " (Formulir akan dibuat di tahap selanjutnya)";
})->name('checkout');
