<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Home\HomeController;


// ── Public ────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');


// ── Guest only ────────────────────────────────────────────────

// ── Guest only ────────────────────────────────────────────────

//ahawa:
// ══════════════════════════════════════════════════════════════
//  EMAIL VERIFICATION — single definition (GLOBAL)
// ══════════════════════════════════════════════════════════════

Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
// ── Guest-only ────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.store');


});
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
 Route::resource('admin/map-categories', App\Http\Controllers\Home\MapCategoryController::class)
    ->parameters(['map-categories' => 'mapCategory']);

Route::resource('admin/map-locations', App\Http\Controllers\Home\MapLocationController::class)
    ->parameters(['map-locations' => 'mapLocation']);// end middleware auth+admin
Route::get('/patrimoine/carte', [App\Http\Controllers\Home\MapPublicController::class, 'show'])->name('heritage-map');
