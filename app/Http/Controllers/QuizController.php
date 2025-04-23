<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = Quiz::orderBy('created_at', 'desc');

        if ($user->role === 'student') {
            $query->whereDoesntHave('attempts', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $quizzes = $query->get();

        return Inertia::render('Quizzes/Index', [
            'user'    => $user,
            'quizzes' => $quizzes,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Quizzes/Create', ['user' => Auth::user()]);
    }

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
            'questions.*.defaultAnswer'  => 'nullable|string',
        ]);

        $quiz = Quiz::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'time_limit'  => $validated['time_limit'] ?? null,
            'created_by'  => Auth::id(),
        ]);

        foreach ($validated['questions'] as $q) {
            $quiz->questions()->create([
                'question_text'   => $q['question_text'],
                'type'            => $q['type'],
                'options'         => $q['options'],
                'correctAnswers'  => $q['correctAnswers'] ?? [],
                'default_answer'  => $q['defaultAnswer'] ?? null,
            ]);
        }

        return redirect()->route('quizzes.index')
                         ->with('success', 'Kvíz sikeresen létrehozva!');
    }

    public function show(Quiz $quiz): Response
    {
        $quiz->load('questions');
        return Inertia::render('Quizzes/Show', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    public function edit(int $id): Response
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        abort_if($quiz->created_by !== Auth::id() && Auth::user()->role !== 'admin', 403);
        return Inertia::render('Quizzes/Edit', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $quiz = Quiz::findOrFail($id);
        abort_if($quiz->created_by !== Auth::id(), 403);

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
            'questions.*.defaultAnswer'  => 'nullable|string',
        ]);

        $quiz->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'time_limit'  => $validated['time_limit'] ?? null,
        ]);

        foreach ($validated['questions'] as $q) {
            if (!empty($q['id'])) {
                Question::findOrFail($q['id'])->update([
                    'question_text'  => $q['question_text'],
                    'type'           => $q['type'],
                    'options'        => $q['options'],
                    'correctAnswers' => $q['correctAnswers'] ?? [],
                    'default_answer' => $q['defaultAnswer'] ?? null,
                ]);
            } else {
                $quiz->questions()->create([
                    'question_text'  => $q['question_text'],
                    'type'           => $q['type'],
                    'options'        => $q['options'],
                    'correctAnswers' => $q['correctAnswers'] ?? [],
                    'default_answer' => $q['defaultAnswer'] ?? null,
                ]);
            }
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Kvíz sikeresen frissítve.');
    }

    public function destroy(int $id)
    {
        $quiz = Quiz::findOrFail($id);

        if (
            (Auth::user()->role === 'teacher' && $quiz->created_by === Auth::id())
            || Auth::user()->role === 'admin'
        ) {
            $quiz->delete();
            return redirect()->route('quizzes.index')
                             ->with('success', 'Kvíz sikeresen törölve.');
        }

        abort(403, 'Nincs jogosultságod törölni ezt a kvízt.');
    }

    public function start(int $id): Response
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return Inertia::render('Quizzes/Show', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    public function submitAnswer(Request $request, int $id): Response
    {
        $quiz      = Quiz::with('questions')->findOrFail($id);
        $validated = $request->validate(['answers' => 'required|array']);

        $percentage = $this->calculatePercentageGrade($quiz, $validated['answers']);

        $quizAttempt = QuizAttempt::create([
            'quiz_id'   => $quiz->id,
            'user_id'   => Auth::id(),
            'score'     => $percentage,
            'answers'   => $validated['answers'],  
            'completed' => true,
        ]);

        $quizResult = QuizResult::create([
            'quiz_attempt_id'  => $quizAttempt->id,
            'score_percentage' => $percentage,
        ]);

        return Inertia::render('Quizzes/SubmitAnswer', [
            'user'        => Auth::user(),
            'quiz'        => $quiz,
            'quizAttempt' => $quizAttempt,
            'quizResult'  => $quizResult,
        ]);
    }

    public function showResult(Quiz $quiz, QuizAttempt $attempt): Response
    {
        $quiz->load('questions');

        // jogosultság-ok
        if (Auth::user()->role === 'student' && $attempt->user_id !== Auth::id()) {
            abort(403);
        }
        if (in_array(Auth::user()->role, ['teacher','admin'])
            && $quiz->created_by !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Quizzes/Result', [
            'user'        => Auth::user(),
            'quiz'        => $quiz,
            'quizAttempt' => $attempt,
            'quizResult'  => $attempt->result,
        ]);
    }

    public function review(Request $request, Quiz $quiz, QuizAttempt $attempt): RedirectResponse
    {
        abort_unless(in_array(Auth::user()->role, ['teacher','admin']), 403);

        $data = $request->validate(['overrides' => 'required|array']);
        $result  = $attempt->result;
        $total   = $quiz->questions()->count();
        $correct = collect($data['overrides'])->filter()->count();
        $newScore = $total ? (int) round($correct / $total * 100) : 0;

        $result->update([
            'score_percentage' => $newScore,
            'is_overridden'    => true,
        ]);

        return redirect()->route('dashboard', [
            'quiz'    => $quiz->id,
            'attempt' => $attempt->id,
        ]);
    }

    private function calculatePercentageGrade(Quiz $quiz, array $answers): int
    {
        $total      = $quiz->questions->count();
        $correctCnt = 0;

        foreach ($quiz->questions as $idx => $question) {
            $ans = $answers[$idx] ?? null;
            if (in_array($question->type, ['multiple_choice','single_choice'], true)) {
                if ($question->correctAnswers == $ans) {
                    $correctCnt++;
                }
            } elseif ($question->type === 'text') {
                $u = trim(strtolower((string)$ans));
                $c = trim(strtolower((string)$question->default_answer));
                if ($u && $c && $u === $c) {
                    $correctCnt++;
                }
            }
        }

        return $total ? (int) round($correctCnt / $total * 100) : 0;
    }

    public function myResults(): Response
    {
        $user    = Auth::user();
        $total   = Quiz::count();
        $attempts = QuizAttempt::with(['quiz','result'])
                   ->where('user_id',$user->id)
                   ->get();

        $stats = [
            'totalQuizzes'     => $total,
            'completedQuizzes' => $attempts->count(),
            'pendingQuizzes'   => $total - $attempts->count(),
        ];

        return Inertia::render('ResultsIndex', [
            'user'     => $user,
            'stats'    => $stats,
            'attempts' => $attempts->map(fn($a)=>[
                'id'          => $a->id,
                'quiz'        => $a->quiz,
                'quizResult'  => $a->result,
            ]),
        ]);
    }
}
