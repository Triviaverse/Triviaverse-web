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

        // Saját próbálkozások időrendben
        $myAttempts = QuizAttempt::with(['quiz', 'result'])
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn($attempt) => [
                'id'           => $attempt->id,
                'quiz'         => $attempt->quiz,
                'date'         => $attempt->created_at->format('Y-m-d'),
                'quizResult'   => [
                    'score_percentage' => $attempt->result?->score_percentage ?? 0,
                    'is_overridden'    => $attempt->result?->is_overridden ?? false,
                ],
            ]);

        // Diákok eredményei
        $studentResults = collect();
        if ($user->role !== 'student') {
            $studentResults = QuizAttempt::with(['quiz', 'user', 'result'])
                ->whereHas('quiz', fn($q) => $q->where('created_by', $user->id))
                ->where('user_id', '!=', $user->id)
                ->orderBy('created_at')
                ->get()
                ->map(fn($a) => [
                    'id'         => $a->id,
                    'quiz'       => $a->quiz,
                    'student'    => $a->user,
                    'quizResult' => [
                        'score_percentage' => $a->result?->score_percentage ?? 0,
                        'is_overridden'    => $a->result?->is_overridden ?? false,
                    ],
                ]);
        }

        $stats = [
            'totalQuizzes'     => $totalQuizzes,
            'completedQuizzes' => $myAttempts->count(),
            'pendingQuizzes'   => $totalQuizzes - $myAttempts->count(),
        ];

        return Inertia::render('Dashboard', [
            'user'           => $user,
            'stats'          => $stats,
            'attempts'       => $myAttempts,
            'studentResults' => $studentResults,
        ]);
    }
}
