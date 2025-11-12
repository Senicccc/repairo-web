<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    UserController,
    AdminController,
    RepairController,
    LoyaltyController,
    PaymentController,
    ProfileController,
    TrackingController,
    SparepartController,
    TechnicianController,
    UsersDashboardController
};

// ============================================================
// PUBLIC ROUTES
// ============================================================

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tracking (public)
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');

// ============================================================
// AUTHENTICATED USER ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard redirect after login
    Route::get('/dashboard', [HomeController::class, 'dashboardRedirect'])
        ->middleware('verified')
        ->name('dashboard');

    // Profile management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Loyalty program
    Route::prefix('loyalty')->group(function () {
        Route::get('/', [LoyaltyController::class, 'showRewards'])->name('loyalty.rewards');
        Route::post('/claim', [LoyaltyController::class, 'claimReward'])->name('loyalty.claim');
        Route::post('/redeem', [LoyaltyController::class, 'redeemCode'])->name('loyalty.redeem');
    });

    // Global sparepart search
    Route::get('/spareparts/search', [SparepartController::class, 'search'])->name('spareparts.search');
});

// ============================================================
// CUSTOMER/USER DASHBOARD ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {
    
    // User Dashboard
    Route::get('/users/dashboard', [UsersDashboardController::class, 'index'])->name('users.dashboard');
    
    // Repair routes for customers
    Route::prefix('users/repairs')->group(function () {
        Route::get('/history', [UsersDashboardController::class, 'repairHistory'])->name('repairs.history');
        Route::get('/{repairId}', [UsersDashboardController::class, 'showRepair'])->name('repairs.show');
    });

    // Create new repair (accessible by customers)
    Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
    Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard & Sections
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/section/{section}', [AdminController::class, 'getSection'])->name('admin.section');
    Route::get('/search/{section}', [AdminController::class, 'search'])->name('admin.search');

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Repair Management
    Route::prefix('repairs')->group(function () {
        Route::get('/', [AdminController::class, 'adminRepairs'])->name('admin.repairs.index');
        Route::put('/{id}', [AdminController::class, 'updateRepair'])->name('admin.repairs.update');
        Route::delete('/{id}', [AdminController::class, 'deleteRepair'])->name('admin.repairs.destroy');
    });

    // Payment Management
    Route::prefix('payments')->group(function () {
        Route::get('/', [AdminController::class, 'adminPayments'])->name('admin.payments.index');
        Route::put('/{id}', [AdminController::class, 'updatePayment'])->name('admin.payments.update');
        Route::delete('/{id}', [AdminController::class, 'deletePayment'])->name('admin.payments.destroy');
    });

    // Loyalty Management
    Route::prefix('loyalty')->group(function () {
        Route::get('/', [AdminController::class, 'adminLoyalty'])->name('admin.loyalty.index');
        Route::put('/{id}', [AdminController::class, 'updateLoyalty'])->name('admin.loyalty.update');
        Route::delete('/{id}', [AdminController::class, 'deleteLoyalty'])->name('admin.loyalty.destroy');
    });

    // Spareparts Management
    Route::prefix('spareparts')->group(function () {
        Route::get('/', [AdminController::class, 'adminSpareparts'])->name('admin.spareparts.index');
        Route::get('/page', [AdminController::class, 'sparepartsPage'])->name('admin.spareparts.page');
        Route::post('/', [AdminController::class, 'storeSparepart'])->name('admin.spareparts.store');
        Route::put('/{id}', [AdminController::class, 'updateSparepart'])->name('admin.spareparts.update');
        Route::delete('/{id}', [AdminController::class, 'deleteSparepart'])->name('admin.spareparts.destroy');
    });
});

// ============================================================
// CASHIER ROUTES
// ============================================================
Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'cashierDashboard'])->name('cashier.dashboard');
    
    // Repair Management
    Route::prefix('repairs')->group(function () {
        Route::get('/', [RepairController::class, 'index'])->name('cashier.repairs.index');
        Route::get('/create', [RepairController::class, 'create'])->name('cashier.repairs.create');
        Route::get('/{id}', [RepairController::class, 'show'])->name('cashier.repairs.show');
        Route::post('/', [RepairController::class, 'store'])->name('cashier.repairs.store');
    });

    // Payment Management
    Route::prefix('payments')->group(function () {
        Route::get('/create/{repair_id}', [PaymentController::class, 'create'])->name('cashier.payments.create');
        Route::post('/', [PaymentController::class, 'store'])->name('cashier.payments.store');
    });

    // Customer Management
    Route::prefix('users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('cashier.users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
    });

    // Loyalty Program
    Route::prefix('loyalty')->group(function () {
        Route::get('/redeem', [LoyaltyController::class, 'redeemPage'])->name('cashier.loyalty.redeem');
        Route::post('/check', [LoyaltyController::class, 'check'])->name('cashier.loyalty.check');
        Route::post('/confirm', [LoyaltyController::class, 'confirmClaim'])->name('cashier.loyalty.confirm');
    });

    // Invoice
    Route::get('/invoice/{id}', function ($id) {
        $repair = \App\Models\Repair::with('user', 'payment')->findOrFail($id);
        return view('invoice.show', compact('repair'));
    })->name('invoice.show');
});

// ============================================================
// TECHNICIAN ROUTES
// ============================================================
Route::prefix('technician')->middleware(['auth', 'role:technician'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');

    // Job Management
    Route::post('/claim/{id}', [TechnicianController::class, 'claimJob'])->name('technician.claim');
    Route::post('/jobs/{id}/update', [TechnicianController::class, 'updateJob'])->name('technician.jobs.update');

    // Sparepart Management
    Route::prefix('spareparts')->group(function () {
        Route::get('/search', [TechnicianController::class, 'searchSpareparts'])->name('technician.spareparts.search');
    });

    // Repair Spareparts
    Route::prefix('repairs')->group(function () {
        Route::get('/{id}/spareparts', [TechnicianController::class, 'getRepairSpareparts'])->name('technician.repairs.spareparts');
        Route::post('/{id}/spareparts', [RepairController::class, 'addSparepart'])->name('technician.repairs.add-sparepart');
        Route::post('/{id}/update-sparepart-field', [TechnicianController::class, 'updateSparepartField'])
            ->name('technician.repairs.update-sparepart-field');
        Route::delete('/{repairId}/spareparts/{sparepartId}', [RepairController::class, 'removeSparepart'])
            ->name('repairs.spareparts.remove');
    });
});

// ============================================================
// AUTH ROUTES (Laravel Breeze/Fortify)
// ============================================================
require __DIR__.'/auth.php';