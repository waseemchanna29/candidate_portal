<?php

use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Candidate\DashboardController as CandidateDashboard;
use App\Http\Controllers\Admin\PricingModelController;
use App\Http\Controllers\Admin\BatchController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Candidate Routes
Route::middleware(['auth', 'candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateDashboard::class, 'index'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
    Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::post('/candidates/{candidate}/approve', [CandidateController::class, 'approve'])->name('candidates.approve');
    Route::post('/candidates/{candidate}/reject',  [CandidateController::class, 'reject'])->name('candidates.reject');
    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/toggle', [CourseController::class, 'toggleStatus'])->name('courses.toggle');

    Route::resource('pricing-models', PricingModelController::class);
    Route::post('/pricing-models/{pricingModel}/toggle', [PricingModelController::class, 'toggleStatus'])
        ->name('pricing-models.toggle');
        
    Route::resource('batches', BatchController::class);
    Route::post('/batches/{batch}/add-seats', [BatchController::class, 'addSeats'])->name('batches.add-seats');

    Route::post('/batches/{batch}/promote/{candidate}', [BatchController::class, 'promoteFromWaitlist'])
     ->name('batches.promote');
});
