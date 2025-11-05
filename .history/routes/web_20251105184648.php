<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\UsersDashboardController;

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
// ADMIN ROUTES
// ============================================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users/{id}', [UserController::class, 'show']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

    
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/section/{section}', [AdminController::class, 'getSection'])->name('admin.section');
    
    // Admin Repairs
    Route::get('/repairs', [RepairController::class, 'adminIndex'])->name('admin.repairs.index');
    Route::post('/repairs', [RepairController::class, 'store'])->name('admin.repairs.store');
    Route::put('/repairs/{id}', [RepairController::class, 'update'])->name('admin.repairs.update');
    Route::delete('/repairs/{id}', [RepairController::class, 'destroy'])->name('admin.repairs.destroy');
    
    // Admin Payments
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('admin.payments.index');
});

// ============================================================
// USERS DASHBOARD & REPAIRS (UNTUK CUSTOMER/USER)
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Users Dashboard
    Route::get('/users/dashboard', [UsersDashboardController::class, 'index'])->name('users.dashboard');
    Route::get('/users/repairs/history', [UsersDashboardController::class, 'repairHistory'])->name('repairs.history');
    Route::get('/users/repairs/{id}', [UsersDashboardController::class, 'showRepair'])->name('repairs.show');
    
    // Repair creation
    Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
    Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
});

// ============================================================
// ROLE: CASHIER  
// ============================================================
Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'cashierDashboard'])->name('cashier.dashboard');
    
    // Repairs
    Route::get('/repairs', [RepairController::class, 'index'])->name('cashier.repairs.index');
    Route::get('/repairs/create', [RepairController::class, 'create'])->name('cashier.repairs.create');
    Route::get('/repairs/{id}', [RepairController::class, 'show'])->name('cashier.repairs.show');
    Route::post('/repairs', [RepairController::class, 'store'])->name('cashier.repairs.store');
    
    // Payments
    Route::get('/payments/create/{repair_id}', [PaymentController::class, 'create'])->name('cashier.payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('cashier.payments.store');
    
    // Users
    Route::get('/users/create', [UserController::class, 'create'])->name('cashier.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    // Loyalty
    Route::get('/loyalty/redeem', [LoyaltyController::class, 'redeemPage'])->name('cashier.loyalty.redeem');
    Route::post('/loyalty/check', [LoyaltyController::class, 'check'])->name('cashier.loyalty.check');
    Route::post('/loyalty/confirm', [LoyaltyController::class, 'confirmClaim'])->name('cashier.loyalty.confirm');
    
    // Invoices
    Route::get('/invoice/{id}', function ($id) {
        $repair = \App\Models\Repair::with('user', 'payment')->findOrFail($id);
        return view('invoice.show', compact('repair'));
    })->name('invoice.show');
});

// ============================================================
// ROLE: TECHNICIAN
// ============================================================
Route::prefix('technician')->middleware(['auth', 'role:technician'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
    
    Route::post('/repairs/{id}/spareparts', [RepairController::class, 'addSparepart'])
        ->name('technician.repairs.add-sparepart');
        
    Route::get('/repairs/{id}/spareparts', [TechnicianController::class, 'getRepairSpareparts'])
        ->name('technician.repairs.spareparts');

        
    
    // Job Management
    Route::post('/claim/{id}', [TechnicianController::class, 'claimJob'])->name('technician.claim');
    Route::post('/jobs/{id}/update', [TechnicianController::class, 'updateJob'])->name('technician.jobs.update');
    
    // Spareparts - FIXED ROUTES
    Route::get('/spareparts/search', [TechnicianController::class, 'searchSpareparts'])->name('technician.spareparts.search');
    Route::get('/repairs/{id}/spareparts', [TechnicianController::class, 'getRepairSpareparts'])->name('technician.repairs.spareparts');
    
    Route::post('/repairs/{id}/spareparts', [RepairController::class, 'addSparepart'])->name('technician.repairs.add-sparepart');
    
    // Route untuk update sparepart field
    Route::post('/repairs/{id}/update-sparepart-field', [TechnicianController::class, 'updateSparepartField'])
        ->name('technician.repairs.update-sparepart-field');
});

// Route untuk remove sparepart (global dengan middleware technician)
Route::middleware(['auth', 'role:technician'])->group(function () {
    Route::delete('/repairs/{repairId}/spareparts/{sparepartId}', [RepairController::class, 'removeSparepart'])
        ->name('repairs.spareparts.remove');
            Route::delete('/repairs/{repairId}/spareparts/{sparepartId}', [RepairController::class, 'removeSparepart'])
        ->name('repairs.spareparts.remove');
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
});

// ============================================================
// SPAREPART SEARCH (GLOBAL)
// ============================================================
Route::get('/spareparts/search', [SparepartController::class, 'search'])
    ->middleware('auth')
    ->name('spareparts.search');

// ============================================================
// AUTH ROUTES
// ============================================================
require __DIR__.'/auth.php';