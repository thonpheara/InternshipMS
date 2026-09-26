<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyVerificationController;
use App\Http\Controllers\Admin\PostApprovalController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Company\ApplicantReviewController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Company\CompanyProfileController;
use App\Http\Controllers\Company\InternshipPostController;
use App\Http\Controllers\Company\MessageController as CompanyMessageController;
use App\Http\Controllers\Student\InternshipBrowseController;
use App\Http\Controllers\Student\MessageController as StudentMessageController;
use App\Http\Controllers\Student\StudentDashboardController;
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
            'admin' => redirect()->route('admin.dashboard'),
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

// In-App Notifications (All authenticated users)
Route::middleware('auth')->prefix('notifications')->as('notifications.')->group(function () {
    Route::get('/{id}/read', [NotificationController::class, 'read'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::delete('/', [NotificationController::class, 'clearAll'])->name('clearAll');
});

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

    // Direct messaging
    Route::get('/messages', [CompanyMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{conversation}', [CompanyMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/start/{application}', [CompanyMessageController::class, 'startFromApplication'])->name('messages.start');
});

// ==========================================
// ADMIN PORTAL ROUTES
// ==========================================
Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Company verification queue
    Route::get('/companies', [CompanyVerificationController::class, 'index'])->name('companies.index');
    Route::put('/companies/{company}', [CompanyVerificationController::class, 'update'])->name('companies.update');

    // Job post moderation queue
    Route::get('/approvals', [PostApprovalController::class, 'index'])->name('approvals.index');
    Route::put('/approvals/{post}', [PostApprovalController::class, 'update'])->name('approvals.update');

    // User Management (CRUD for Students & Companies)
    Route::resource('users', UserManagementController::class);

    // Placement & Outcome Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
});

// Public storage route fallback (ensures file preview/download works without relying on symlinks)
Route::get('/storage/{path}', function (\Illuminate\Http\Request $request, string $path) {
    $fullPath = storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $path));
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404, 'File not found.');
    }

    $filename = basename($fullPath);
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // If requested as download or non-previewable format (e.g. docx), download with exact original filename
    if ($request->has('download') || !in_array($extension, ['pdf', 'png', 'jpg', 'jpeg'])) {
        return response()->download($fullPath, $filename, [
            'Content-Disposition' => 'attachment; filename="' . addcslashes($filename, '"\\') . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
        ]);
    }

    $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . addcslashes($filename, '"\\') . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*');
