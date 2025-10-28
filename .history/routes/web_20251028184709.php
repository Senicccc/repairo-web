<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;

// Halaman utama (public)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard umum (redirect sesuai role)
Route::get('/dashboard', [HomeController::class, 'dashboardRedirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ============================================================
// PROFILE ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ============================================================
// ROLE-BASED ROUTES
// ============================================================

// USER (Customer)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/repairs', [RepairController::class, 'index'])->name('user.repairs');
});

// CASHIER
Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier/dashboard', [HomeController::class, 'cashierDashboard'])->name('cashier.dashboard');
    Route::get('/cashier/repairs', [RepairController::class, 'index'])->name('cashier.repairs');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/cashier/repairs', [RepairController::class, 'store'])->name('repairs.store');
    Route::post('/cashier/repairs/{id}/finish', [RepairController::class, 'update'])->name('cashier.repairs.finish');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/invoice/{id}', function($id) {
        $repair = \App\Models\Repair::with('user', 'payment')->findOrFail($id);
        return view('invoice.show', compact('repair'));
    })->name('invoice.show');
});

// TECHNICIAN
Route::middleware(['auth', 'role:technician'])->group(function () {
    Route::get('/technician/dashboard', [RepairController::class, 'technicianDashboard'])->name('technician.dashboard');
    Route::get('/technician/jobs', [RepairController::class, 'technicianJobs'])->name('technician.jobs');
    Route::post('/technician/repairs/{id}/update', [RepairController::class, 'update'])->name('technician.repairs.update');
    Route::post('/technician/claim/{id}', [RepairController::class, 'claim'])->name('technician.claim');
});

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');
});

// ============================================================
// TRACKING (Public)
// ============================================================
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');

// ============================================================
// LOYALTY PROGRAM ROUTES
// ============================================================
Route::middleware('auth')->group(function(){
    Route::get('/loyalty', [App\Http\Controllers\LoyaltyController::class, 'showRewards'])->name('loyalty.rewards');
    Route::post('/loyalty/claim', [App\Http\Controllers\LoyaltyController::class, 'claimReward'])->name('loyalty.claim');
    // web.php (middleware web + auth , but only cashier/admin can access in real app)
    Route::post('/loyalty/redeem', [App\Http\Controllers\LoyaltyController::class, 'redeemCode'])->name('loyalty.redeem');
    Route::post('/loyalty/check', [LoyaltyController::class, 'check'])->name('loyalty.check');
    Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::post('/loyalty/check', [LoyaltyController::class, 'check'])->name('loyalty.check');
    Route::post('/loyalty/confirm-claim', [LoyaltyController::class, 'confirmClaim'])->name('loyalty.confirmClaim');
    });
});

Route::get('/admin/dashboard', [App\Http\Controllers\HomeController::class, 'adminDashboard'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'role:admin']);

    use App\Http\Controllers\SparepartController;

Route::prefix('admin')->group(function () {
    Route::resource('spareparts', SparepartController::class)->names('admin.spareparts');
});

    


// ============================================================
// AUTH ROUTES
// ============================================================
require __DIR__.'/auth.php';