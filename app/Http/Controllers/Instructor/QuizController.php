<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * Store a new quiz with questions and answers
     */
    public function store(Request $request, Section $section)
    {
        try {
            // Validate basic quiz information
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'quiz_type' => 'required|in:practice,graded,final',
                'duration_minutes' => 'nullable|integer|min:5|max:180',
                'pass_percentage' => 'nullable|integer|min:0|max:100',
                'max_attempts' => 'nullable|integer|min:1|max:10',
                'randomize_questions' => 'nullable|boolean',
                'show_results' => 'nullable|boolean',
                'questions' => 'required|array|min:1',
            ]);

            DB::beginTransaction();

            // Create quiz
            $quiz = $section->quizzes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'quiz_type' => $request->quiz_type,
                'duration_minutes' => $request->duration_minutes,
                'pass_percentage' => $request->pass_percentage ?? 70,
                'max_attempts' => $request->max_attempts ?? 3,
                'randomize_questions' => $request->boolean('randomize_questions'),
                'show_results' => $request->boolean('show_results', true),
                'sort_order' => $section->quizzes()->max('sort_order') + 1,
            ]);

            // Create questions and answers
            foreach ($request->questions as $questionData) {
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['type'],
                    'points' => $questionData['points'] ?? 1,
                    'sort_order' => $quiz->questions()->count() + 1,
                ]);

                // Handle different question types
                if ($questionData['type'] === 'multiple_choice') {
                    // Create multiple choice answers
                    $correctAnswerId = $questionData['correct_answer'];
                    
                    foreach ($questionData['answers'] as $answerId => $answerText) {
                        $question->answers()->create([
                            'answer_text' => $answerText,
                            'is_correct' => ($answerId == $correctAnswerId),
                        ]);
                    }
                } elseif ($questionData['type'] === 'true_false') {
                    // Create true/false answers
                    $correctAnswer = $questionData['correct_answer'];
                    
                    $question->answers()->create([
                        'answer_text' => 'True',
                        'is_correct' => ($correctAnswer === 'true'),
                    ]);
                    
                    $question->answers()->create([
                        'answer_text' => 'False',
                        'is_correct' => ($correctAnswer === 'false'),
                    ]);
                } elseif ($questionData['type'] === 'fill_blank') {
                    // Create fill in the blank answer
                    $question->answers()->create([
                        'answer_text' => $questionData['correct_answer'],
                        'is_correct' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Quiz created successfully with ' . $quiz->questions()->count() . ' questions!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check your input.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Quiz creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified quiz
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions.answers', 'section.course']);
        
        return view('instructor.quizzes.show', compact('quiz'));
    }

    /**
     * Update the specified quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'quiz_type' => 'required|in:practice,graded,final',
                'duration_minutes' => 'nullable|integer|min:5|max:180',
                'pass_percentage' => 'nullable|integer|min:0|max:100',
                'max_attempts' => 'nullable|integer|min:1|max:10',
            ]);

            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
                'quiz_type' => $request->quiz_type,
                'duration_minutes' => $request->duration_minutes,
                'pass_percentage' => $request->pass_percentage,
                'max_attempts' => $request->max_attempts,
                'randomize_questions' => $request->boolean('randomize_questions'),
                'show_results' => $request->boolean('show_results'),
            ]);

            return redirect()->back()->with('success', 'Quiz updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Quiz update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified quiz
     */
    public function destroy(Quiz $quiz)
    {
        try {
            $quiz->delete();
            
            return redirect()->back()->with('success', 'Quiz deleted successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Quiz deletion failed: ' . $e->getMessage());
        }
    }
}
