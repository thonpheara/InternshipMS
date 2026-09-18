<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlacementSupervisorController;
use App\Http\Controllers\Admin\PostApprovalController;
use App\Http\Controllers\Admin\StudentEligibilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Company\ApplicantReviewController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Company\CompanyProfileController;
use App\Http\Controllers\Company\EvaluationController;
use App\Http\Controllers\Company\InternLogReviewController;
use App\Http\Controllers\Company\InternshipPostController;
use App\Http\Controllers\Company\MessageController as CompanyMessageController;
use App\Http\Controllers\Student\InternshipBrowseController;
use App\Http\Controllers\Student\MessageController as StudentMessageController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\WeeklyLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin', 'coordinator' => redirect()->route('admin.dashboard'),
            'company' => redirect()->route('company.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// STUDENT PORTAL ROUTES
// ==========================================
Route::prefix('student')->as('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');

    // Job browsing & applications
    Route::get('/posts', [InternshipBrowseController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [InternshipBrowseController::class, 'show'])->name('posts.show');
    Route::post('/posts/{post}/apply', [InternshipBrowseController::class, 'apply'])->name('posts.apply');
    Route::get('/applications', [InternshipBrowseController::class, 'applications'])->name('applications.index');

    // Weekly activity logs
    Route::get('/logs', [WeeklyLogController::class, 'index'])->name('logs.index');
    Route::post('/logs', [WeeklyLogController::class, 'store'])->name('logs.store');

    // Direct messaging
    Route::get('/messages', [StudentMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{conversation}', [StudentMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/start/{application}', [StudentMessageController::class, 'startFromApplication'])->name('messages.start');

    // Resume preview / download / delete
    Route::get('/resume/preview', [StudentDashboardController::class, 'previewResume'])->name('resume.preview');
    Route::delete('/resume', [StudentDashboardController::class, 'deleteResume'])->name('resume.delete');
});

// ==========================================
// COMPANY PORTAL ROUTES
// ==========================================
Route::prefix('company')->as('company.')->middleware(['auth', 'role:company'])->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CompanyProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [CompanyProfileController::class, 'updateProfile'])->name('profile.update');

    // Manage internship posts
    Route::resource('posts', InternshipPostController::class)->except(['show']);

    // Review applicants
    Route::get('/applicants', [ApplicantReviewController::class, 'index'])->name('applicants.index');
    Route::put('/applicants/{application}', [ApplicantReviewController::class, 'updateStatus'])->name('applicants.update');
    Route::get('/applicants/{application}/resume', [ApplicantReviewController::class, 'resume'])->name('applicants.resume');

    // Intern weekly logs approval
    Route::get('/logs', [InternLogReviewController::class, 'index'])->name('logs.index');
    Route::put('/logs/{log}', [InternLogReviewController::class, 'update'])->name('logs.update');

    // Evaluations
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/create/{placement}', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/evaluations/{placement}', [EvaluationController::class, 'store'])->name('evaluations.store');

    // Direct messaging
    Route::get('/messages', [CompanyMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{conversation}', [CompanyMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/start/{application}', [CompanyMessageController::class, 'startFromApplication'])->name('messages.start');
});

// ==========================================
// ADMIN & COORDINATOR PORTAL ROUTES
// ==========================================
Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin,coordinator'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Job post moderation queue
    Route::get('/approvals', [PostApprovalController::class, 'index'])->name('approvals.index');
    Route::put('/approvals/{post}', [PostApprovalController::class, 'update'])->name('approvals.update');

    // Student eligibility management
    Route::get('/students', [StudentEligibilityController::class, 'index'])->name('students.index');
    Route::put('/students/{student}', [StudentEligibilityController::class, 'update'])->name('students.update');

    // Placements and supervisor allocation
    Route::get('/placements', [PlacementSupervisorController::class, 'index'])->name('placements.index');
    Route::put('/placements/{placement}', [PlacementSupervisorController::class, 'update'])->name('placements.update');
});

// Public storage route fallback (ensures file preview/download works without relying on symlinks)
Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    abort_if(!file_exists($fullPath), 404, 'File not found.');
    return response()->file($fullPath);
})->where('path', '.*');
