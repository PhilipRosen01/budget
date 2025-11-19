<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetTemplateController;
use App\Http\Controllers\BudgetPreferenceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseGoalController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'onboarding.check'])->name('dashboard');

Route::middleware(['auth', 'onboarding.check'])->group(function () {
    // Onboarding routes (must come first to intercept new users)
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index')->withoutMiddleware(['onboarding.check']);
    Route::get('/onboarding/step1', [OnboardingController::class, 'step1'])->name('onboarding.step1')->withoutMiddleware(['onboarding.check']);
    Route::post('/onboarding/step1', [OnboardingController::class, 'processStep1'])->name('onboarding.process-step1')->withoutMiddleware(['onboarding.check']);
    Route::get('/onboarding/step2', [OnboardingController::class, 'step2'])->name('onboarding.step2')->withoutMiddleware(['onboarding.check']);
    Route::post('/onboarding/step2', [OnboardingController::class, 'processStep2'])->name('onboarding.process-step2')->withoutMiddleware(['onboarding.check']);
    Route::get('/onboarding/step3', [OnboardingController::class, 'step3'])->name('onboarding.step3')->withoutMiddleware(['onboarding.check']);
    Route::post('/onboarding/step3', [OnboardingController::class, 'processStep3'])->name('onboarding.process-step3')->withoutMiddleware(['onboarding.check']);
    Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete')->withoutMiddleware(['onboarding.check']);
    Route::post('/onboarding/to-dashboard', [OnboardingController::class, 'toDashboard'])->name('onboarding.to-dashboard')->withoutMiddleware(['onboarding.check']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Budget template management routes
    Route::resource('budget-templates', BudgetTemplateController::class);
    Route::post('/budget-templates/generate-next-month', [BudgetTemplateController::class, 'generateNextMonth'])
        ->name('budget-templates.generate-next-month');
    Route::post('/budget-templates/generate-current-month', [BudgetTemplateController::class, 'generateCurrentMonth'])
        ->name('budget-templates.generate-current-month');
    
    // Custom budget routes (must come BEFORE resource routes to avoid conflicts)
    Route::delete('/budgets/month', [BudgetController::class, 'destroyMonth'])
        ->name('budgets.destroy-month');
    Route::post('/budgets/create-from-templates', [BudgetController::class, 'createFromTemplates'])
        ->name('budgets.create-from-templates');
    Route::get('/budgets/select-templates', [BudgetController::class, 'selectTemplates'])
        ->name('budgets.select-templates');
    Route::post('/budgets/create-from-selected', [BudgetController::class, 'createFromSelectedTemplates'])
        ->name('budgets.create-from-selected');
    Route::get('/budgets/setup', [BudgetController::class, 'setupBudgets'])
        ->name('budgets.setup');
    Route::post('/budgets/create-automatic', [BudgetController::class, 'createAutomaticBudgets'])
        ->name('budgets.create-automatic');
    
    // Monthly budget management routes (resource routes come after custom routes)
    Route::resource('budgets', BudgetController::class);
    
    // Purchase management routes
    Route::resource('purchases', PurchaseController::class);
    
    // Budget preferences routes
    Route::patch('/budget-preferences', [BudgetPreferenceController::class, 'update'])
        ->name('budget-preferences.update');
    Route::post('/budget-preferences/generate-templates', [BudgetPreferenceController::class, 'generateAutomaticTemplates'])
        ->name('budget-preferences.generate-templates');
    Route::get('/budget-preferences/preview-templates', [BudgetPreferenceController::class, 'previewTemplates'])
        ->name('budget-preferences.preview-templates');
    
    // Purchase goals (rewards) management routes
    Route::resource('purchase-goals', PurchaseGoalController::class);
});

require __DIR__.'/auth.php';
