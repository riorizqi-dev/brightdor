<?php

use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InvitationController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\MyBookingController;
use App\Http\Controllers\Frontend\PasswordResetController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\VendorController;
use App\Http\Controllers\Frontend\VendorRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('login')->name('frontend.login.')->group(function () {
    Route::get('/', [LoginController::class, 'create'])->name('create');
    Route::post('/', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->prefix('register')->name('frontend.register.')->group(function () {
    Route::get('/', [RegisterController::class, 'create'])->name('create');
    Route::post('/', [RegisterController::class, 'store'])->name('store');
});

Route::prefix('lupa-password')->name('frontend.password.')->group(function () {
    Route::get('/', [PasswordResetController::class, 'create'])->name('request');
    Route::post('/', [PasswordResetController::class, 'store'])->middleware('throttle:5,1')->name('email');
    Route::get('/reset/{token}', [PasswordResetController::class, 'edit'])->name('reset');
    Route::post('/reset', [PasswordResetController::class, 'update'])->middleware('throttle:5,1')->name('update');
});

Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
    ->name('password.reset');

Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::get('/{categorySlug}', [VendorController::class, 'index'])->name('category');
});

Route::get('/vendor/{slug}', [VendorController::class, 'show'])->name('vendors.show');
Route::post('/vendor/{slug}/booking', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('vendors.booking');

Route::middleware('auth')->prefix('daftar-vendor')->name('vendors.register.')->group(function () {
    Route::get('/', [VendorRegisterController::class, 'create'])->name('create');
    Route::post('/', [VendorRegisterController::class, 'store'])->middleware('throttle:5,1')->name('store');
});

Route::middleware('auth')->prefix('booking-saya')->name('my-bookings.')->group(function () {
    Route::get('/', [MyBookingController::class, 'index'])->name('index');
    Route::post('/{booking}/batal', [MyBookingController::class, 'cancel'])->name('cancel');
    Route::get('/{booking}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/{booking}/review', [ReviewController::class, 'store'])->name('review.store');
});

Route::prefix('i')->name('invitations.')->group(function () {
    Route::get('/{slug}', [InvitationController::class, 'show'])->name('show');
    Route::post('/{slug}/rsvp', [InvitationController::class, 'storeRsvp'])
        ->middleware('throttle:20,1')
        ->name('rsvp');
});