<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\AdminController;

Route::view('/', 'welcome')->name('welcome');
Route::view('/welcome', 'welcome');

// After login, redirect based on role
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'profileForm'])->name('profile');
    Route::post('/profile', [StudentController::class, 'saveProfile'])->name('profile.save');
    Route::get('/apply', [StudentController::class, 'applyForm'])->name('apply');
    Route::post('/apply', [StudentController::class, 'applySubmit'])->name('apply.submit');
});

// Institution Routes
Route::middleware(['auth', 'role:institution'])->prefix('institution')->name('institution.')->group(function () {
    Route::get('/dashboard', [InstitutionController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [InstitutionController::class, 'profileForm'])->name('profile');
    Route::post('/profile', [InstitutionController::class, 'saveProfile'])->name('profile.save');
    Route::post('/verify/{id}', [InstitutionController::class, 'verify'])->name('verify');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/institutions', [AdminController::class, 'institutions'])->name('institutions');
    Route::post('/institutions/{id}/approve', [AdminController::class, 'approveInstitution'])->name('institutions.approve');
    Route::post('/institutions/{id}/reject', [AdminController::class, 'rejectInstitution'])->name('institutions.reject');
    Route::get('/scholarships', [AdminController::class, 'scholarships'])->name('scholarships');
    Route::post('/scholarships/{id}/approve', [AdminController::class, 'approve'])->name('scholarships.approve');
    Route::post('/scholarships/{id}/reject', [AdminController::class, 'reject'])->name('scholarships.reject');
    Route::get('/students', [AdminController::class, 'students'])->name('students');
});

require __DIR__.'/auth.php';
