<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// --- Auth -------------------------------------------------------------
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// --- Dashboard ----------------------------------------------------------
Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

// --- Invitations ----------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/invitations/new-company', [InvitationController::class, 'storeForNewCompany'])
        ->middleware('role:SuperAdmin')
        ->name('invitations.new-company');

    Route::post('/invitations/company', [InvitationController::class, 'storeForCompany'])
        ->middleware('role:Admin')
        ->name('invitations.company');
});

// Acceptance links are public (the invitee has no account yet) but must
Route::middleware('signed')->group(function () {
    Route::get('/invitations/{invitation}/accept', [InvitationController::class, 'accept'])
        ->name('invitations.accept');

    Route::post('/invitations/{invitation}/accept', [InvitationController::class, 'complete'])
        ->name('invitations.complete');
});

// --- Short urls -----------------------------
Route::middleware('auth')->group(function () {
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');
});

// --- Public redirect ------------------------------------------------------
Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9]+')
    ->name('short.redirect');
