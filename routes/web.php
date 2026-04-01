<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SendoraSettingController;
use App\Models\Submission;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public pages
Route::get('/', [SubmissionController::class, 'create'])->name('submissions.create');
Route::get('/daftar/{category}', [SubmissionController::class, 'form'])->name('submissions.form');
Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');

// Dashboard with stats per category
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'amk' => [
                'total' => Submission::where('category', 'amk')->count(),
                'pending' => Submission::where('category', 'amk')->where('status', 'pending')->count(),
                'verified' => Submission::where('category', 'amk')->where('status', 'verified')->count(),
                'rejected' => Submission::where('category', 'amk')->where('status', 'rejected')->count(),
            ],
            'mbsp' => [
                'total' => Submission::where('category', 'mbsp')->count(),
                'pending' => Submission::where('category', 'mbsp')->where('status', 'pending')->count(),
                'verified' => Submission::where('category', 'mbsp')->where('status', 'verified')->count(),
                'rejected' => Submission::where('category', 'mbsp')->where('status', 'rejected')->count(),
            ],
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Submissions management
    Route::get('/admin/submissions', [SubmissionController::class, 'index'])->name('admin.submissions');
    Route::post('/admin/submissions/{submission}/verify', [SubmissionController::class, 'verify'])->name('admin.submissions.verify');
    Route::post('/admin/submissions/{submission}/reject', [SubmissionController::class, 'reject'])->name('admin.submissions.reject');

    // Sendora settings
    Route::get('/admin/sendora', [SendoraSettingController::class, 'edit'])->name('admin.sendora.edit');
    Route::post('/admin/sendora', [SendoraSettingController::class, 'update'])->name('admin.sendora.update');
    Route::post('/admin/sendora/test', [SendoraSettingController::class, 'testConnection'])->name('admin.sendora.test');
});

require __DIR__.'/auth.php';
