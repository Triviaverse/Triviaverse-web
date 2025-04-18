<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::orderBy('created_at', 'desc')->get();

        return Inertia::render('Quizzes/Index', [
            'user'    => Auth::user(),
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(): Response
    {
        return Inertia::render('Quizzes/Create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a newly created quiz in storage.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'                      => 'required|string|max:255',
            'description'                => 'nullable|string',
            'time_limit'                 => 'nullable|integer|min:1',
            'questions'                  => 'required|array|min:1',
            'questions.*.question_text'  => 'required|string|max:1000',
            'questions.*.type'           => 'required|in:multiple_choice,text',
            'questions.*.options'        => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correctAnswers' => 'array',
        ]);

        $quiz = Quiz::create([
            'title'       => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'time_limit'  => $validatedData['time_limit'] ?? null,
            'created_by'  => Auth::id(),
        ]);

        foreach ($validatedData['questions'] as $questionData) {
            $question = $quiz->questions()->create([
                'question_text'  => $questionData['question_text'],
                'type'           => $questionData['type'],
                'options'        => json_encode($questionData['options']),             // JSON mentés
                'correctAnswers' => json_encode($questionData['correctAnswers'] ?? []) // JSON mentés
            ]);
        }

        return redirect()->route('quizzes.index')->with('success', 'Kvíz sikeresen létrehozva!');
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quiz $quiz): Response
    {
        return Inertia::render('Quizzes/Show', [
            'quiz' => $quiz->load('questions'),
        ]);
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        if ($quiz->created_by !== Auth::id()) {
            abort(403, "Nincs jogosultságod a kvíz szerkesztéséhez.");
        }

        // JSON dekódolás a válaszlehetőségekre és helyes válaszokra
        $quiz->questions->transform(function ($question) {
            if ($question->type === 'multiple_choice') {
                $question->options        = json_decode($question->options, true) ?? [];
                $question->correctAnswers = json_decode($question->correctAnswers, true) ?? [];
            }
            return $question;
        });

        return Inertia::render('Quizzes/Edit', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        if ($quiz->created_by !== Auth::id()) {
            abort(403, "Nincs jogosultságod a kvíz módosítására.");
        }

        $validatedData = $request->validate([
            'title'                      => 'required|string|max:255',
            'description'                => 'nullable|string',
            'time_limit'                 => 'nullable|integer|min:1',
            'questions'                  => 'required|array|min:1',
            'questions.*.question_text'  => 'required|string|max:255',
            'questions.*.type'           => 'required|in:multiple_choice,text',
            'questions.*.options'        => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correctAnswers' => 'array',
        ]);

        $quiz->update([
            'title'       => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'time_limit'  => $validatedData['time_limit'] ?? null,
        ]);

        foreach ($validatedData['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                $question = Question::findOrFail($questionData['id']);
                $question->update([
                    'question_text'  => $questionData['question_text'],
                    'type'           => $questionData['type'],
                    'options'        => json_encode($questionData['options']),
                    'correctAnswers' => json_encode($questionData['correctAnswers'] ?? []),
                ]);
            } else {
                $quiz->questions()->create([
                    'question_text'  => $questionData['question_text'],
                    'type'           => $questionData['type'],
                    'options'        => json_encode($questionData['options']),
                    'correctAnswers' => json_encode($questionData['correctAnswers'] ?? []),
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Kvíz sikeresen frissítve.');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);

        // Csak az a tanár törölheti, aki létrehozta
        if (Auth::user()->role === 'teacher' && $quiz->created_by === Auth::id()) {
            $quiz->delete();
            return redirect()->route('quizzes.index')->with('success', 'Kvíz sikeresen törölve.');
        }

        return redirect()->route('quizzes.index')->with('error', 'Nincs jogosultságod törölni ezt a kvízt.');
    }

    public function start($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        // Decode options and correct answers for multiple-choice questions
        $quiz->questions->transform(function ($question) {
            if ($question->type === 'multiple_choice') {
                $question->options = json_decode($question->options, true) ?? [];
            }
            return $question;
        });

        return Inertia::render('Quizzes/Show', [
            'user' => Auth::user(),
            'quiz' => $quiz,
        ]);
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validatedData = $request->validate([
            'answers' => 'required|array',
        ]);

        // Process the answers (e.g., calculate score, save results, etc.)
        // Example: Save the results to the database
        $score = 0;
        foreach ($quiz->questions as $index => $question) {
            if ($question->type === 'multiple_choice') {
                $correctAnswers = json_decode($question->correctAnswers, true);
                if ($correctAnswers == $validatedData['answers'][$index]) {
                    $score++;
                }
            } elseif ($question->type === 'text') {
                // Handle text-based answers
            }
        }

        return redirect()->route('dashboard')->with('success', 'Kvíz sikeresen beküldve!');
    }

    public function submitAnswer(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        $validatedData = $request->validate([
            'answers' => 'required|array',
        ]);

        // Calculate the percentage grade (placeholder function)
        $percentage = $this->calculatePercentageGrade($quiz, $validatedData['answers']);

        // Save the quiz attempt
        $quizAttempt = $quiz->attempts()->create([
            'user_id'   => Auth::id(),
            'score'     => $percentage,
            'answers'   => json_encode($validatedData['answers']),
            'completed' => true,
        ]);

        return redirect()->route('quizzes.index')->with('success', 'Kvíz sikeresen beküldve!');
    }

    private function calculatePercentageGrade($quiz, $answers)
    {
        // TODO: Implement the logic to calculate the percentage grade based on the quiz and answers
        return 0;
    }
}