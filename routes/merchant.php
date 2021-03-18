<?php

use App\Http\Controllers\Merchant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Merchant\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Merchant\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Merchant\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Merchant\Auth\NewPasswordController;
use App\Http\Controllers\Merchant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Merchant\Auth\RegisteredUserController;
use App\Http\Controllers\Merchant\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::name('merchant.')->prefix('merchant')->group(function () {
    // define all merchant route
    Route::get('/', function () {
        return redirect()->route('merchant.login');
    });

    Route::get('/register', [RegisteredUserController::class, 'create'])
                    ->middleware('guest:merchant')
                    ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
                    ->middleware('guest:merchant');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                    ->middleware('guest:merchant')
                    ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                    ->middleware('guest:merchant');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->middleware('guest:merchant')
                    ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->middleware('guest:merchant')
                    ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->middleware('guest:merchant')
                    ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                    ->middleware('guest:merchant')
                    ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                    ->middleware('auth:merchant')
                    ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware(['auth:merchant', 'signed', 'throttle:6,1'])
                    ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['auth:merchant', 'throttle:6,1'])
                    ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->middleware('auth:merchant')
                    ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                    ->middleware('auth:merchant');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->middleware('auth:merchant')
                    ->name('logout');

         
    Route::middleware(['auth:merchant', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });
});