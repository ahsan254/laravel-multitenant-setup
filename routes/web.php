<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Landlord\TenantController;
use Illuminate\Support\Facades\Route;

// Root environment routes (myapp.test) - also works with 127.0.0.1:8000
Route::group([], function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('index');

    // Onboarding routes
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/step1', [OnboardingController::class, 'step1'])->name('step1');
        Route::match(['get', 'post'], '/step2', [OnboardingController::class, 'step2'])->name('step2');
        Route::match(['get', 'post'], '/step3', [OnboardingController::class, 'step3'])->name('step3');
        Route::match(['get', 'post'], '/step4', [OnboardingController::class, 'step4'])->name('step4');
        Route::match(['get', 'post'], '/step5', [OnboardingController::class, 'step5'])->name('step5');
        Route::get('/success', [OnboardingController::class, 'success'])->name('success');
    });
});

// Landlord environment routes (landlord.myapp.test)
Route::prefix('landlord')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('landlord.tenants.index');
    });

    Route::prefix('tenants')->name('landlord.tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('index');
        Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
        Route::delete('/{tenant}', [TenantController::class, 'destroy'])->name('destroy');
    });
});

// Tenant environment routes ({tenant}.myapp.test)
Route::prefix('tenant/{tenant}')->middleware(['resolve.tenant'])->group(function () {
    Route::get('/', function () {
        return view('tenant.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
