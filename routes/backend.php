<?php

use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Backend\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Backend\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Backend\Auth\NewPasswordController;
use App\Http\Controllers\Backend\Auth\PasswordResetLinkController;
use App\Http\Controllers\Backend\Auth\RegisteredUserController;
use App\Http\Controllers\Backend\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::name('backend.')->prefix('backend')->group(function () {
    // define all backend route
    Route::get('/', function () {
        return redirect()->route('backend.login');
    });

    Route::get('/register', [RegisteredUserController::class, 'create'])
                    ->middleware('guest:backend')
                    ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
                    ->middleware('guest:backend');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                    ->middleware('guest:backend')
                    ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                    ->middleware('guest:backend');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->middleware('guest:backend')
                    ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->middleware('guest:backend')
                    ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->middleware('guest:backend')
                    ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                    ->middleware('guest:backend')
                    ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                    ->middleware('auth:backend')
                    ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware(['auth:backend', 'signed', 'throttle:6,1'])
                    ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['auth:backend', 'throttle:6,1'])
                    ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->middleware('auth:backend')
                    ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                    ->middleware('auth:backend');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->middleware('auth:backend')
                    ->name('logout');

      
    Route::middleware(['auth:backend', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });
});