<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PaymentCockpitController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// All authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Tours
    Route::resource('tours', TourController::class);
    Route::post('tours/{tour}/toggle-status', [TourController::class, 'toggleStatus'])->name('tours.toggle-status');

    // Clients
    Route::resource('clients', ClientController::class);

    // Bookings
    Route::resource('bookings', BookingController::class);

    // Installments
    Route::post('bookings/{booking}/installments', [InstallmentController::class, 'store'])->name('installments.store');
    Route::put('installments/{installment}', [InstallmentController::class, 'update'])->name('installments.update');
    Route::delete('installments/{installment}', [InstallmentController::class, 'destroy'])->name('installments.destroy');
    Route::post('installments/{installment}/mark-paid', [InstallmentController::class, 'markPaid'])->name('installments.mark-paid');
    Route::post('installments/{installment}/resend-email', [InstallmentController::class, 'resendEmail'])->name('installments.resend-email');
    Route::post('installments/{installment}/toggle-email', [InstallmentController::class, 'toggleEmail'])->name('installments.toggle-email');

    // Payment Cockpit
    Route::get('pagamentos', [PaymentCockpitController::class, 'index'])->name('payments.index');

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email-templates/{email_template}/preview', [EmailTemplateController::class, 'preview'])->name('email-templates.preview');

    // Settings (admin & manager only)
    Route::middleware('can.manage')->group(function () {
        Route::get('configuracoes', [SettingController::class, 'index'])->name('settings.index');
        Route::put('configuracoes', [SettingController::class, 'update'])->name('settings.update');
    });

    // Logs (admin & manager only)
    Route::middleware('can.manage')->group(function () {
        Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    });

    // User Management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
