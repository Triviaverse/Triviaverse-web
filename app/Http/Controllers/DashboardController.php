<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statisztikák lekérdezése
        $totalQuizzes = Quiz::count();
        $completedQuizzes = QuizAttempt::where('user_id', $user->id)->where('completed', true)->count();
        $pendingQuizzes = Quiz::whereDoesntHave('attempts', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return Inertia::render('Dashboard', [
            'user' => $user,
            'stats' => [
                'totalQuizzes' => $totalQuizzes,
                'completedQuizzes' => $completedQuizzes,
                'pendingQuizzes' => $pendingQuizzes,
            ],
            'quizzes' => Quiz::all() ?? [], 
        ]);
    }
}

