<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\ConversationApiController;
use App\Http\Controllers\Api\FileApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\StudentApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Android Mobile Application (Java / Kotlin / Retrofit)
|--------------------------------------------------------------------------
|
| All routes in this file are automatically prefixed with "/api" and
| have the "api" middleware group applied.
|
*/

// Public Authentication Endpoints
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthApiController::class, 'register'])->name('api.auth.register');
});

// Protected API Routes requiring Sanctum Bearer Token
Route::middleware('auth:sanctum')->group(function () {

    // 1. Authenticated User Profile & Session
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthApiController::class, 'me'])->name('api.auth.me');
        Route::post('/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');
        Route::put('/password', [AuthApiController::class, 'updatePassword'])->name('api.auth.password');
        Route::post('/avatar', [AuthApiController::class, 'updateAvatar'])->name('api.auth.avatar.update');
        Route::delete('/avatar', [AuthApiController::class, 'removeAvatar'])->name('api.auth.avatar.remove');
    });

    // 2. Student Portal API
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentApiController::class, 'dashboard'])->name('api.student.dashboard');
        Route::get('/profile', [StudentApiController::class, 'getProfile'])->name('api.student.profile');
        Route::post('/profile', [StudentApiController::class, 'updateProfile'])->name('api.student.profile.update');
        Route::post('/resume', [StudentApiController::class, 'uploadResume'])->name('api.student.resume.upload');
        Route::delete('/resume', [StudentApiController::class, 'deleteResume'])->name('api.student.resume.delete');

        // Internship browsing & application
        Route::get('/posts', [StudentApiController::class, 'browsePosts'])->name('api.student.posts');
        Route::get('/posts/{id}', [StudentApiController::class, 'showPost'])->name('api.student.posts.show');
        Route::post('/posts/{id}/apply', [StudentApiController::class, 'applyPost'])->name('api.student.posts.apply');
        Route::get('/applications', [StudentApiController::class, 'myApplications'])->name('api.student.applications');
        Route::get('/applications/{id}', [StudentApiController::class, 'showApplication'])->name('api.student.applications.show');
    });

    // 3. Company Portal API
    Route::prefix('company')->middleware('role:company')->group(function () {
        Route::get('/dashboard', [CompanyApiController::class, 'dashboard'])->name('api.company.dashboard');
        Route::get('/profile', [CompanyApiController::class, 'getProfile'])->name('api.company.profile');
        Route::post('/profile', [CompanyApiController::class, 'updateProfile'])->name('api.company.profile.update');

        // Manage listings
        Route::get('/posts', [CompanyApiController::class, 'listPosts'])->name('api.company.posts');
        Route::post('/posts', [CompanyApiController::class, 'storePost'])->name('api.company.posts.store');
        Route::get('/posts/{id}', [CompanyApiController::class, 'showPost'])->name('api.company.posts.show');
        Route::put('/posts/{id}', [CompanyApiController::class, 'updatePost'])->name('api.company.posts.update');
        Route::delete('/posts/{id}', [CompanyApiController::class, 'deletePost'])->name('api.company.posts.delete');

        // Review applicants
        Route::get('/applicants', [CompanyApiController::class, 'listApplicants'])->name('api.company.applicants');
        Route::get('/applicants/{id}', [CompanyApiController::class, 'showApplicant'])->name('api.company.applicants.show');
        Route::put('/applicants/{id}/status', [CompanyApiController::class, 'updateApplicantStatus'])->name('api.company.applicants.status');
    });

    // 4. In-App Direct Messaging & Chat
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationApiController::class, 'index'])->name('api.conversations.index');
        Route::get('/{id}', [ConversationApiController::class, 'show'])->name('api.conversations.show');
        Route::post('/{id}/messages', [ConversationApiController::class, 'sendMessage'])->name('api.conversations.messages.send');
        Route::post('/start/{applicationId}', [ConversationApiController::class, 'startFromApplication'])->name('api.conversations.start');
    });

    // 5. In-App Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationApiController::class, 'index'])->name('api.notifications.index');
        Route::get('/unread-count', [NotificationApiController::class, 'unreadCount'])->name('api.notifications.unread_count');
        Route::post('/{id}/read', [NotificationApiController::class, 'markAsRead'])->name('api.notifications.read');
        Route::post('/read-all', [NotificationApiController::class, 'markAllAsRead'])->name('api.notifications.read_all');
        Route::delete('/{id}', [NotificationApiController::class, 'destroy'])->name('api.notifications.destroy');
        Route::delete('/', [NotificationApiController::class, 'clearAll'])->name('api.notifications.clear_all');
    });

    // 6. Resume / File Access
    Route::prefix('files')->group(function () {
        Route::get('/applications/{applicationId}/resume', [FileApiController::class, 'downloadApplicationResume'])->name('api.files.application_resume');
        Route::get('/students/{studentId?}/resume', [FileApiController::class, 'downloadProfileResume'])->name('api.files.student_resume');
    });
});
