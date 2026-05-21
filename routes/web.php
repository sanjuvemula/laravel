<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/welcome', 'welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/schemes/{id}/tiers', [SchemeController::class, 'getTiers'])
    ->middleware('auth')
    ->name('schemes.tiers');

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StudentController::class, 'profileForm'])->name('profile');
        Route::post('/profile', [StudentController::class, 'saveProfile'])->name('profile.save');
        Route::get('/apply', [StudentController::class, 'applyForm'])->name('apply');
        Route::post('/apply', [StudentController::class, 'applySubmit'])->name('apply.submit');
    });

Route::middleware(['auth', 'role:institution'])
    ->prefix('institution')
    ->name('institution.')
    ->group(function () {
        Route::get('/dashboard', [InstitutionController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [InstitutionController::class, 'profileForm'])->name('profile');
        Route::post('/profile', [InstitutionController::class, 'saveProfile'])->name('profile.save');
        Route::post('/verify/{id}', [InstitutionController::class, 'verify'])->name('verify');

        Route::get('/schemes', [SchemeController::class, 'index'])->name('schemes.index');
        Route::get('/schemes/create', [SchemeController::class, 'create'])->name('schemes.create');
        Route::post('/schemes', [SchemeController::class, 'store'])->name('schemes.store');
        Route::get('/schemes/{id}/edit', [SchemeController::class, 'edit'])->name('schemes.edit');
        Route::put('/schemes/{id}', [SchemeController::class, 'update'])->name('schemes.update');
        Route::delete('/schemes/{id}', [SchemeController::class, 'destroy'])->name('schemes.destroy');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/institutions', [AdminController::class, 'institutions'])->name('institutions');
        Route::post('/institutions/{id}/approve', [AdminController::class, 'approveInstitution'])->name('institutions.approve');
        Route::post('/institutions/{id}/reject', [AdminController::class, 'rejectInstitution'])->name('institutions.reject');
        Route::get('/scholarships', [AdminController::class, 'scholarships'])->name('scholarships');
        Route::post('/scholarships/{id}/approve', [AdminController::class, 'approve'])->name('scholarships.approve');
        Route::post('/scholarships/{id}/reject', [AdminController::class, 'reject'])->name('scholarships.reject');
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export.csv');
    });

require __DIR__ . '/auth.php';
