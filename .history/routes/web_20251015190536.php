<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// User routes
Route::get('/users', [UserController::class, 'index']);

// Repair routes
Route::get('/repairs', [RepairController::class, 'index']);
Route::post('/repairs', [RepairController::class, 'store']);
Route::put('/repairs/{id}', [RepairController::class, 'update']);
Route::delete('/repairs/{id}', [RepairController::class, 'destroy']);

// Payment routes
Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);