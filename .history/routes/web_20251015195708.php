<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================
// DASHBOARD UMUM (redirect sesuai role)
// ============================================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->role) {
        case 'user': return redirect()->route('user.dashboard');
        case 'cashier': return redirect()->route('cashier.dashboard');
        case 'technician': return redirect()->route('technician.dashboard');
        case 'admin': return redirect()->route('admin.dashboard');
        default: abort(403);
    }
})->middleware(['auth', 'verified']);

// ============================================================
// PROFILE ROUTES (all authenticated users)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// ROLE-BASED ROUTES
// ============================================================

// --------------------- USER (Customer) ---------------------
Route::prefix('user')->name('user.')->middleware(['auth','role:user'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('dashboard');
    Route::get('/repairs', [RepairController::class, 'index'])->name('repairs');
});

// --------------------- CASHIER -----------------------------
Route::prefix('cashier')->name('cashier.')->middleware(['auth','role:cashier'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'cashierDashboard'])->name('dashboard');
    Route::get('/repairs', [RepairController::class, 'index'])->name('repairs');
    Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
    Route::post('/repairs/{id}/finish', [RepairController::class, 'finish'])->name('repairs.finish');
});

// --------------------- TECHNICIAN --------------------------
Route::prefix('technician')->name('technician.')->middleware(['auth','role:technician'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'technicianDashboard'])->name('dashboard');
    Route::get('/repairs', [RepairController::class, 'index'])->name('repairs');
    Route::post('/repairs/{id}/update', [RepairController::class, 'update'])->name('repairs.update');
});

// --------------------- ADMIN -------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
});

// ============================================================
// AUTH ROUTES
// ============================================================
require __DIR__.'/auth.php';