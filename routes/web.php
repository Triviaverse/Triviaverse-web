<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/quizzes/create', [QuizController::class, 'create'])->middleware(['auth'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store'); 
    Route::get('/quizzes/manage', [QuizController::class, 'manage'])->name('quizzes.manage');
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/quizzes/{id}/start', [QuizController::class, 'start'])->name('quizzes.start'); 
    Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
});

require __DIR__.'/auth.php';

