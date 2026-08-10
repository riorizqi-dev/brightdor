<?php

use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\VendorController;
use App\Http\Controllers\Frontend\VendorRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('login')->name('frontend.login.')->group(function () {
    Route::get('/', [LoginController::class, 'create'])->name('create');
    Route::post('/', [LoginController::class, 'store'])->name('store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::get('/{categorySlug}', [VendorController::class, 'index'])->name('category');
});

Route::get('/vendor/{slug}', [VendorController::class, 'show'])->name('vendors.show');
Route::post('/vendor/{slug}/booking', [BookingController::class, 'store'])->name('vendors.booking');

Route::prefix('daftar-vendor')->name('vendors.register.')->group(function () {
    Route::get('/', [VendorRegisterController::class, 'create'])->name('create');
    Route::post('/', [VendorRegisterController::class, 'store'])->name('store');
});
