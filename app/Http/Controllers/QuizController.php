<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::orderBy('created_at', 'desc')->get();

        return Inertia::render('Quizzes/Index', [
            'user' => Auth::user(),
            'quizzes' => $quizzes
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
             'title' => 'required|string|max:255',
             'description' => 'nullable|string',
             'time_limit' => 'nullable|integer|min:1',
             'questions' => 'required|array|min:1',
             'questions.*.question_text' => 'required|string|max:1000',
             'questions.*.type' => 'required|in:multiple_choice,text',
         ]);
 
         $quiz = Quiz::create([
             'title' => $validatedData['title'],
             'description' => $validatedData['description'] ?? null,
             'time_limit' => $validatedData['time_limit'] ?? null,
             'created_by' => Auth::id(),
         ]);
 
         foreach ($validatedData['questions'] as $questionData) {
             $quiz->questions()->create([
                 'question_text' => $questionData['question_text'],
                 'type' => $questionData['type'],
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
            'quiz' => $quiz->load('questions')
        ]);
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz): Response
    {
        return Inertia::render('Quizzes/Edit', [
            'quiz' => $quiz->load('questions')
        ]);
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_answers' => 'required|array|min:1',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        $quiz->questions()->delete();
        foreach ($validated['questions'] as $question) {
            $quiz->questions()->create($question);
        }

        return Redirect::route('quizzes.index')->with('success', 'Kvíz sikeresen frissítve!');
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
}