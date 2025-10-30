// Users Dashboard Routes
Route::middleware(['auth'])->group(function () {
Route::get('/users/dashboard', [UsersDashboardController::class, 'index'])->name('users.dashboard');
Route::get('/users/repairs/history', [UsersDashboardController::class, 'repairHistory'])->name('repairs.history');
Route::get('/users/repairs/{id}', [UsersDashboardController::class, 'showRepair'])->name('repairs.show');

// Repair creation
Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
});