<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',   [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quizzes
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/manage', [QuizController::class, 'manage'])->name('quizzes.manage');
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/quizzes/{id}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submitAnswer'])->name('quizzes.submitAnswer');
    Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::get('/quizzes/{quiz}/{attempt}/result', [QuizController::class, 'showResult'])->name('quizzes.result');
    Route::post('/quizzes/{quiz}/{attempt}/review', [QuizController::class, 'review'])->name('quizzes.review');
    Route::get('/results', [QuizController::class, 'myResults'])->name('results.index');
});

Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         Route::get('/users',        [AdminController::class, 'index'])->name('index');
         Route::get('/users/{user}/edit',   [AdminController::class, 'edit'])->name('edit');
         Route::put('/users/{user}',        [AdminController::class, 'update'])->name('update');
         Route::delete('/users/{user}',     [AdminController::class, 'destroy'])->name('destroy');
     });


require __DIR__ . '/auth.php';
