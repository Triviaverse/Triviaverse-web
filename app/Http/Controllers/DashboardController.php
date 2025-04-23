<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        $totalQuizzes = Quiz::count();

        $attempts = QuizAttempt::with(['quiz', 'results'])
            ->where('user_id', $user->id)
            ->get()
            ->map(function($attempt) {
                return [
                    'id'         => $attempt->id,
                    'quiz'       => $attempt->quiz,
                    'quizResult' => [
                        // Ha nincs kapcsolódó eredmény, 0-t adunk
                        'score_percentage' => $attempt->results?->score_percentage ?? 0,
                    ],
                ];
            });

        $stats = [
            'totalQuizzes'     => $totalQuizzes,
            'completedQuizzes' => $attempts->count(),
            'pendingQuizzes'   => $totalQuizzes - $attempts->count(),
        ];

        return Inertia::render('Dashboard', [
            'user'     => $user,
            'stats'    => $stats,
            'attempts' => $attempts,
        ]);
    }
}