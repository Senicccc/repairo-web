<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UserDashboardController;

// ============================================================
// HALAMAN UTAMA (PUBLIC)
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard umum (redirect sesuai role)
Route::get('/dashboard', [HomeController::class, 'dashboardRedirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ============================================================
// PROFILE
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// ROLE: USER (CUSTOMER)
// ============================================================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/repairs', [RepairController::class, 'index'])->name('user.repairs');
});

// ============================================================
// ROLE: CASHIER
// ============================================================
Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier/dashboard', [HomeController::class, 'cashierDashboard'])->name('cashier.dashboard');
    Route::get('/cashier/repairs', [RepairController::class, 'index'])->name('cashier.repairs');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/cashier/repairs', [RepairController::class, 'store'])->name('repairs.store');
    Route::post('/cashier/repairs/{id}/finish', [RepairController::class, 'update'])->name('cashier.repairs.finish');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/invoice/{id}', function ($id) {
        $repair = \App\Models\Repair::with('user', 'payment')->findOrFail($id);
        return view('invoice.show', compact('repair'));
    })->name('invoice.show');
});

// ============================================================
// ROLE: TECHNICIAN
// ============================================================
Route::middleware(['auth', 'role:technician'])->group(function () {
    Route::get('/technician/dashboard', [RepairController::class, 'technicianDashboard'])->name('technician.dashboard');
    Route::post('/technician/repairs/{id}/update', [RepairController::class, 'update'])->name('technician.repairs.update');
    Route::post('/technician/claim/{id}', [RepairController::class, 'claim'])->name('technician.claim');
    
    // Repair update route
    Route::post('/repairs/{id}', [RepairController::class, 'update'])->name('repairs.update');
    
    // Sparepart routes
    Route::get('/spareparts/search', [SparepartController::class, 'search'])->name('spareparts.search');
    Route::post('/repairs/{id}/spareparts', [RepairController::class, 'addSparepart'])->name('repairs.addSparepart');

    // Sparepart save
    Route::get('/api/repairs/{id}/spareparts', function($id) {
        $spareparts = \App\Models\RepairSparepart::where('repair_id', $id)->get();
        return response()->json($spareparts);
    })->middleware(['auth', 'role:technician']);

    // Remove software
    Route::delete('/repairs/{repairId}/spareparts/{sparepartId}', [RepairController::class, 'removeSparepart'])
    ->middleware(['auth', 'role:technician'])
    ->name('repairs.spareparts.remove');
});

// ============================================================
// ROLE: ADMIN
// ============================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');
});

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/users/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/users/repairs/history', [UserDashboardController::class, 'repairHistory'])->name('repairs.history');
    Route::get('/users/repairs/{id}', [UserDashboardController::class, 'showRepair'])->name('repairs.show');
    
    // Existing repair routes for users
    Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
    Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
});

// ============================================================
// TRACKING (PUBLIC)
// ============================================================
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');

// ============================================================
// LOYALTY PROGRAM
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/loyalty', [LoyaltyController::class, 'showRewards'])->name('loyalty.rewards');
    Route::post('/loyalty/claim', [LoyaltyController::class, 'claimReward'])->name('loyalty.claim');
    Route::post('/loyalty/redeem', [LoyaltyController::class, 'redeemCode'])->name('loyalty.redeem');

    Route::middleware(['role:cashier'])->group(function () {
        Route::post('/loyalty/check', [LoyaltyController::class, 'check'])->name('loyalty.check');
        Route::post('/loyalty/confirm-claim', [LoyaltyController::class, 'confirmClaim'])->name('loyalty.confirmClaim');
    });
});

// ============================================================
// AUTH ROUTES
// ============================================================
require __DIR__.'/auth.php';