<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetTemplateController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Budget template management routes
    Route::resource('budget-templates', BudgetTemplateController::class);
    Route::post('/budget-templates/generate-next-month', [BudgetTemplateController::class, 'generateNextMonth'])
        ->name('budget-templates.generate-next-month');
    
    // Monthly budget management routes
    Route::resource('budgets', BudgetController::class);
    
    // Purchase management routes
    Route::resource('purchases', PurchaseController::class);
});

require __DIR__.'/auth.php';
