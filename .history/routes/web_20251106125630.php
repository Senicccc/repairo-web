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

    // Redirect dashboard by role after login
    Route::get('/dashboard', [HomeController::class, 'dashboardRedirect'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    // ============================================================
    // PROFILE ROUTES (Authenticated Users Only)
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
        
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/section/{section}', [AdminController::class, 'getSection'])->name('admin.section');

        // User Management
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Repair Management
        Route::get('/repairs', [AdminController::class, 'adminRepairs'])->name('admin.repairs.index');
        Route::put('/repairs/{id}', [AdminController::class, 'updateRepair'])->name('admin.repairs.update');
        Route::delete('/repairs/{id}', [AdminController::class, 'deleteRepair'])->name('admin.repairs.destroy');

        // Payment Management
        Route::get('/payments', [AdminController::class, 'adminPayments'])->name('admin.payments.index');
        Route::put('/payments/{id}', [AdminController::class, 'updatePayment'])->name('admin.payments.update');
        Route::delete('/payments/{id}', [AdminController::class, 'deletePayment'])->name('admin.payments.destroy');

        // Loyalty Management
        Route::get('/loyalty', [AdminController::class, 'adminLoyalty'])->name('admin.loyalty.index');
        
        // Spareparts Management
        Route::get('/spareparts', [AdminController::class, 'adminSpareparts'])->name('admin.spareparts.index');

        // Loyalty Management
        Route::get('/loyalty', [AdminController::class, 'adminLoyalty'])->name('admin.loyalty.index');
        Route::delete('/loyalty/{id}', [AdminController::class, 'deleteLoyalty'])->name('admin.loyalty.destroy');
        Route::put('/loyalty/{id}', [AdminController::class, 'updateLoyalty'])->name('admin.loyalty.update');

    });

    // ============================================================
    // USER DASHBOARD & REPAIR ROUTES (CUSTOMER/USER)
    // ============================================================
    Route::middleware(['auth'])->group(function () {

        // User Dashboard
        Route::get('/users/dashboard', [UsersDashboardController::class, 'index'])->name('users.dashboard');
        
        // Repair History
        Route::get('/users/repairs/history', [UsersDashboardController::class, 'repairHistory'])->name('repairs.history');
        Route::get('/users/repairs/{id}', [UsersDashboardController::class, 'showRepair'])->name('repairs.show');

        // Create New Repair
        Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
        Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
    });

    // ============================================================
    // CASHIER ROUTES
    // ============================================================
    Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {

        Route::get('/dashboard', [HomeController::class, 'cashierDashboard'])->name('cashier.dashboard');
        
        // Repair Management (Cashier)
        Route::get('/repairs', [RepairController::class, 'index'])->name('cashier.repairs.index');
        Route::get('/repairs/create', [RepairController::class, 'create'])->name('cashier.repairs.create');
        Route::get('/repairs/{id}', [RepairController::class, 'show'])->name('cashier.repairs.show');
        Route::post('/repairs', [RepairController::class, 'store'])->name('cashier.repairs.store');
        
        // Payment Management
        Route::get('/payments/create/{repair_id}', [PaymentController::class, 'create'])->name('cashier.payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('cashier.payments.store');
        
        // Customer Account Creation
        Route::get('/users/create', [UserController::class, 'create'])->name('cashier.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        
        // Loyalty Program
        Route::get('/loyalty/redeem', [LoyaltyController::class, 'redeemPage'])->name('cashier.loyalty.redeem');
        Route::post('/loyalty/check', [LoyaltyController::class, 'check'])->name('cashier.loyalty.check');
        Route::post('/loyalty/confirm', [LoyaltyController::class, 'confirmClaim'])->name('cashier.loyalty.confirm');
        
        // Invoice View
        Route::get('/invoice/{id}', function ($id) {
            $repair = \App\Models\Repair::with('user', 'payment')->findOrFail($id);
            return view('invoice.show', compact('repair'));
        })->name('invoice.show');
    });

    // ============================================================
    // TECHNICIAN ROUTES
    // ============================================================
    Route::prefix('technician')->middleware(['auth', 'role:technician'])->group(function () {

        Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');

        // Job Claiming and Updates
        Route::post('/claim/{id}', [TechnicianController::class, 'claimJob'])->name('technician.claim');
        Route::post('/jobs/{id}/update', [TechnicianController::class, 'updateJob'])->name('technician.jobs.update');
        
        // Sparepart Management
        Route::get('/spareparts/search', [TechnicianController::class, 'searchSpareparts'])->name('technician.spareparts.search');
        Route::get('/repairs/{id}/spareparts', [TechnicianController::class, 'getRepairSpareparts'])->name('technician.repairs.spareparts');
        Route::post('/repairs/{id}/spareparts', [RepairController::class, 'addSparepart'])->name('technician.repairs.add-sparepart');

        // Update Sparepart Field
        Route::post('/repairs/{id}/update-sparepart-field', [TechnicianController::class, 'updateSparepartField'])
            ->name('technician.repairs.update-sparepart-field');

        // Remove sparepart
        Route::delete('/repairs/{repairId}/spareparts/{sparepartId}', [RepairController::class, 'removeSparepart'])
            ->name('repairs.spareparts.remove');
    });

    // ============================================================
    // TRACKING ROUTES (PUBLIC)
    // ============================================================
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');

    // ============================================================
    // LOYALTY PROGRAM ROUTES
    // ============================================================
    Route::middleware('auth')->group(function () {
        Route::get('/loyalty', [LoyaltyController::class, 'showRewards'])->name('loyalty.rewards');
        Route::post('/loyalty/claim', [LoyaltyController::class, 'claimReward'])->name('loyalty.claim');
        Route::post('/loyalty/redeem', [LoyaltyController::class, 'redeemCode'])->name('loyalty.redeem');
    });

    // ============================================================
    // GLOBAL SPAREPART SEARCH
    // ============================================================
    Route::get('/spareparts/search', [SparepartController::class, 'search'])
        ->middleware('auth')
        ->name('spareparts.search');

    // ============================================================
    // AUTH ROUTES
    // ============================================================
    require __DIR__.'/auth.php';    