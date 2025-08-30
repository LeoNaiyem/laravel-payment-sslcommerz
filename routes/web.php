<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// sslcommerz routes
Route::get('/pay', [PaymentController::class, 'payNow'])->name('pay.now');
Route::post('/success', [PaymentController::class, 'success'])->name('sslc.success');
Route::post('/fail', [PaymentController::class, 'fail'])->name('sslc.failure');
Route::post('/cancel', [PaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('/ipn', [PaymentController::class, 'ipn'])->name('sslc.ipn');

// auth routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
