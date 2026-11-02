<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAll');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Submissions show & download (accessible by owning student or assignment teacher)
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');

    // Classes shared view
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');

    // Teacher only
    Route::middleware('role:teacher')->group(function () {
        Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{class}', [ClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->name('classes.destroy');
        Route::post('/classes/{class}/regenerate-code', [ClassController::class, 'regenerateCode'])->name('classes.regenerate');
        Route::delete('/classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])->name('classes.removeStudent');

        Route::resource('subjects', SubjectController::class);

        Route::resource('assignments', AssignmentController::class)->except(['show']);
        Route::patch('/assignments/{assignment}/publish', [AssignmentController::class, 'publish'])->name('assignments.publish');
        Route::patch('/assignments/{assignment}/archive', [AssignmentController::class, 'archive'])->name('assignments.archive');
        Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/grades/{submission}', [GradeController::class, 'store'])->name('grades.store');
        Route::patch('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    });

    // Student only
    Route::middleware('role:student')->group(function () {
        Route::post('/classes/join', [ClassController::class, 'join'])->name('classes.join');
        Route::delete('/classes/{class}/leave', [ClassController::class, 'leave'])->name('classes.leave');
        Route::get('/my-assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments');
        Route::get('/my-grades', [GradeController::class, 'studentIndex'])->name('student.grades');
        Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::patch('/submissions/{submission}/resubmit', [SubmissionController::class, 'resubmit'])->name('submissions.resubmit');
    });

    // Detail kelas dan tugas
    Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
});

