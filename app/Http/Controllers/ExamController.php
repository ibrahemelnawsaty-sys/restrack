<?php

namespace App\Http\Controllers;

use App\Models\ExamAttempt;
use App\Models\Level;
use App\Services\ExamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(private ExamService $exams) {}

    public function start(Level $level): View|RedirectResponse
    {
        abort_unless($level->is_published, 404);

        if ($level->questions()->where('is_published', true)->count() === 0) {
            return redirect()->route('levels.show', $level)
                ->with('status', 'لا توجد أسئلة منشورة لهذا المستوى بعد.');
        }

        $attempt = $this->exams->start(auth()->user(), $level);
        $questions = $this->exams->questionsFor($attempt);

        return view('student.exam', compact('level', 'attempt', 'questions'));
    }

    public function submit(Request $request, Level $level): RedirectResponse
    {
        $attempt = ExamAttempt::where('id', $request->input('attempt_id'))
            ->where('user_id', auth()->id())
            ->where('level_id', $level->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $this->exams->grade($attempt, (array) $request->input('answers', []));

        return redirect()->route('exam.result', $attempt);
    }

    public function result(ExamAttempt $attempt): View
    {
        abort_unless($attempt->user_id === auth()->id(), 403);
        abort_if(is_null($attempt->completed_at), 404);

        $attempt->load('level');
        $questions = $this->exams->questionsFor($attempt);

        return view('student.exam-result', compact('attempt', 'questions'));
    }
}
