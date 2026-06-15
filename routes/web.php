<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])->name('login.store');
});

Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Sales only - Write access to prospects
    Route::middleware(['role:sales'])->group(function () {
        Route::get('/prospects/create', [\App\Http\Controllers\Sales\ProspectController::class, 'create'])->name('prospects.create');
        Route::post('/prospects', [\App\Http\Controllers\Sales\ProspectController::class, 'store'])->name('prospects.store');
        Route::get('/prospects/{prospect}/edit', [\App\Http\Controllers\Sales\ProspectController::class, 'edit'])->name('prospects.edit');
        Route::put('/prospects/{prospect}', [\App\Http\Controllers\Sales\ProspectController::class, 'update'])->name('prospects.update');
        Route::delete('/prospects/{prospect}', [\App\Http\Controllers\Sales\ProspectController::class, 'destroy'])->name('prospects.destroy');
        Route::post('/prospects/{prospect}/evaluations', [\App\Http\Controllers\Sales\EvaluationController::class, 'store'])->name('prospects.evaluations.store');
    });

    // Prospect & Resource Routes (Accessible by all authorized roles)
    Route::get('/prospects', [\App\Http\Controllers\Sales\ProspectController::class, 'index'])->name('prospects.index')->middleware('role:sales,admin,pimpinan');
    Route::get('/prospects/{prospect}', [\App\Http\Controllers\Sales\ProspectController::class, 'show'])->name('prospects.show')->middleware('role:sales,admin,pimpinan');
    Route::get('/guide', [\App\Http\Controllers\GuideController::class, 'index'])->name('guide.index')->middleware('role:sales,admin,pimpinan');
    
    // Admin & Pimpinan Routes for Sales Management
    Route::middleware(['role:admin,pimpinan'])->group(function () {
        Route::get('/admin/sales', [\App\Http\Controllers\Admin\SalesManagementController::class, 'index'])->name('admin.sales.index');
        Route::get('/admin/sales/create', [\App\Http\Controllers\Admin\SalesManagementController::class, 'create'])->name('admin.sales.create');
        Route::post('/admin/sales', [\App\Http\Controllers\Admin\SalesManagementController::class, 'store'])->name('admin.sales.store');
        Route::get('/admin/sales/{user}/edit', [\App\Http\Controllers\Admin\SalesManagementController::class, 'edit'])->name('admin.sales.edit');
        Route::put('/admin/sales/{user}', [\App\Http\Controllers\Admin\SalesManagementController::class, 'update'])->name('admin.sales.update');
        Route::delete('/admin/sales/{user}', [\App\Http\Controllers\Admin\SalesManagementController::class, 'destroy'])->name('admin.sales.destroy');
    });

    // Admin Routes (Accessible Only by Admin)
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::resource('criterias', \App\Http\Controllers\Admin\CriteriaController::class);
        Route::resource('criterias.sub-criterias', \App\Http\Controllers\Admin\SubCriteriaController::class);
    });
});
