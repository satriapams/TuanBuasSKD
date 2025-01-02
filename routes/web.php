<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AuthController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


    // Routes for Blog Posts
    Route::resource('posts', PostController::class);
});

Route::get('/backup-database', [BackupController::class, 'backupDatabase'])->name('backup.database');
Route::get('/download-backup/{fileName}', [BackupController::class, 'downloadBackup'])->name('backup.download');
Route::post('/restore-database', [BackupController::class, 'restoreDatabase'])->name('backup.restore');
Route::post('login', [AuthController::class, 'login']);



require __DIR__.'/auth.php';
