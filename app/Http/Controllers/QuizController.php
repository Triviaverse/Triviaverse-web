<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    /**
     * Quizzes listázása.
     */
    public function index(): Response
    {
        $quizzes = Quiz::orderBy('created_at', 'desc')->get();

        return Inertia::render('Quizzes/Index', [
            'user'    => Auth::user(),
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Új kvíz létrehozása form.
     */
    public function create(): Response
    {
        return Inertia::render('Quizzes/Create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Új kvíz mentése.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                      => 'required|string|max:255',
            'description'                => 'nullable|string',
            'time_limit'                 => 'nullable|integer|min:1',
            'questions'                  => 'required|array|min:1',
            'questions.*.question_text'  => 'required|string|max:1000',
            'questions.*.type'           => 'required|in:multiple_choice,single_choice,text',
            'questions.*.options'        => 'required_if:questions.*.type,multiple_choice,single_choice|array|min:2',
            'questions.*.correctAnswers' => 'nullable|array',
            'questions.*.defaultAnswer' => 'nullable|string',
        ]);

        $quiz = Quiz::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'time_limit'  => $validated['time_limit'] ?? null,
            'created_by'  => Auth::id(),
        ]);

        foreach ($validated['questions'] as $q) {
            $quiz->questions()->create([
                'question_text'  => $q['question_text'],
                'type'           => $q['type'],
                'options'        => $q['options'],                  
                'correctAnswers' => $q['correctAnswers'] ?? [],  
                'default_answer'  => $q['defaultAnswer'] ?? null, 
            ]);
        }

        return redirect()->route('quizzes.index')
                         ->with('success', 'Kvíz sikeresen létrehozva!');
    }

    /**
     * Kvíz megtekintése.
     */
    public function show(Quiz $quiz): Response
    {
        // A Question modell $casts miatt a options és correctAnswers már tömb lesz
        $quiz->load('questions');

        return Inertia::render('Quizzes/Show', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    /**
     * Kvíz szerkesztése form.
     */
    public function edit(int $id): Response
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        if ($quiz->created_by !== Auth::id()) {
            abort(403, "Nincs jogosultságod a kvíz szerkesztéséhez.");
        }

        return Inertia::render('Quizzes/Edit', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    /**
     * Kvíz frissítése.
     */
    public function update(Request $request, int $id)
    {
        $quiz = Quiz::findOrFail($id);

        if ($quiz->created_by !== Auth::id()) {
            abort(403, "Nincs jogosultságod a kvíz módosítására.");
        }

        $validated = $request->validate([
            'title'                      => 'required|string|max:255',
            'description'                => 'nullable|string',
            'time_limit'                 => 'nullable|integer|min:1',
            'questions'                  => 'required|array|min:1',
            'questions.*.id'             => 'nullable|integer|exists:questions,id',
            'questions.*.question_text'  => 'required|string|max:1000',
            'questions.*.type'           => 'required|in:multiple_choice,single_choice,text',
            'questions.*.options'        => 'required_if:questions.*.type,multiple_choice,single_choice|array|min:2',
            'questions.*.correctAnswers' => 'nullable|array',
            'questions.*.defaultAnswer' => 'nullable|string',
        ]);

        $quiz->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'time_limit'  => $validated['time_limit'] ?? null,
        ]);

        foreach ($validated['questions'] as $q) {
            if (!empty($q['id'])) {
                // Létező kérdés frissítése
                $question = Question::findOrFail($q['id']);
                $question->update([
                    'question_text'  => $q['question_text'],
                    'type'           => $q['type'],
                    'options'        => $q['options'],
                    'correctAnswers' => $q['correctAnswers'] ?? [],
                    'default_answer'  => $q['defaultAnswer'] ?? null,
                ]);
            } else {
                // Új kérdés
                $quiz->questions()->create([
                    'question_text'  => $q['question_text'],
                    'type'           => $q['type'],
                    'options'        => $q['options'],
                    'correctAnswers' => $q['correctAnswers'] ?? [],
                    'default_answer'  => $q['defaultAnswer'] ?? null,
                ]);
            }
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Kvíz sikeresen frissítve.');
    }

    /**
     * Kvíz törlése.
     */
    public function destroy(int $id)
    {
        $quiz = Quiz::findOrFail($id);

        if (Auth::user()->role === 'teacher' && $quiz->created_by === Auth::id()) {
            $quiz->delete();
            return redirect()->route('quizzes.index')
                             ->with('success', 'Kvíz sikeresen törölve.');
        }

        return redirect()->route('quizzes.index')
                         ->with('error', 'Nincs jogosultságod törölni ezt a kvízt.');
    }

    /**
     * Kvíz kitöltése (start).
     */
    public function start(int $id): Response
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        return Inertia::render('Quizzes/Show', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    /**
     * Kvíz válaszainak beküldése és elmentése.
     */
    public function submitAnswer(Request $request, int $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        // Százalékos eredmény kiszámítása
        $percentage = $this->calculatePercentageGrade($quiz, $validated['answers']);

        // Mentés
        $quizAttempt = $quiz->attempts()->create([
            'user_id'   => Auth::id(),
            'score'     => $percentage,
            'answers'   => json_encode($validated['answers']),
            'completed' => true,
        ]);

        $quizResult = QuizResult::create([
            'quiz_attempt_id' => $quizAttempt->id,
            'score_percentage'=> $percentage,
         ]);         

        // Mindig Inertia-komponensként térünk vissza:
        return Inertia::render('Quizzes/SubmitAnswer', [
            'user'        => Auth::user(),
            'quiz'        => $quiz,
            'quizAttempt' => $quizAttempt,
            'quizResult' => $quizResult,
        ]);
    }

    public function showResult(int $id): Response
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        // Ha diák, akkor csak a saját legutóbbi próbálkozása
        $attempt = $quiz->attempts()
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->firstOrFail();

        // Feltételezzük, hogy van kapcsolódó QuizResult modell
        $result = $attempt->results; 

        return Inertia::render('Quizzes/Result', [
            'user'        => Auth::user(),
            'quiz'        => $quiz,
            'quizAttempt' => $attempt,
            'quizResult'  => $result,
        ]);
    }

    /**
     * Segédfüggvény: százalékos pontszám.
     */
    private function calculatePercentageGrade(Quiz $quiz, array $answers): int
    {
        // Tetszőleges logika, vagy maradhat a placeholder
        $total      = $quiz->questions->count();
        $correctCnt = 0;

        foreach ($quiz->questions as $idx => $question) {
            if ($question->type === 'multiple_choice' || $question->type === 'single_choice') {
                if (isset($answers[$idx]) && $question->correctAnswers == $answers[$idx]) {
                    $correctCnt++;
                }
            }
            // Beírós kérdés esetén itt jöhet a saját logika...
        }

        return $total > 0
            ? (int) round($correctCnt / $total * 100)
            : 0;
    }

    public function myResults(): Response
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
                        'score_percentage' => $attempt->results?->score_percentage ?? 0,
                    ],
                ];
            });

        $stats = [
            'totalQuizzes'     => $totalQuizzes,
            'completedQuizzes' => $attempts->count(),
            'pendingQuizzes'   => $totalQuizzes - $attempts->count(),
        ];

        return Inertia::render('ResultsIndex', [
            'user'     => $user,
            'stats'    => $stats,
            'attempts' => $attempts,
        ]);
    }
}
