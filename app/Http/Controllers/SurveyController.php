<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * The post-level survey (owner note م12). Offered once the learner has passed the level;
     * it is what makes the "continuous improvement" claim in Quality Assurance real.
     */
    public function show(Request $request, Level $level): View|RedirectResponse
    {
        $user = $request->user();

        if (! $this->hasPassed($user, $level)) {
            return redirect()->route('levels.show', $level);
        }

        $existing = SurveyResponse::where('user_id', $user->id)->where('level_id', $level->id)->first();

        return view('student.survey', compact('level', 'existing'));
    }

    public function store(Request $request, Level $level): RedirectResponse
    {
        $user = $request->user();

        if (! $this->hasPassed($user, $level)) {
            throw ValidationException::withMessages(['level' => 'الاستبيان يفتح بعد اجتياز المستوى.']);
        }

        $data = $request->validate([
            'content_quality' => 'required|integer|between:1,5',
            'clarity' => 'required|integer|between:1,5',
            'speaker_quality' => 'required|integer|between:1,5',
            'technical_quality' => 'required|integer|between:1,5',
            'ease_of_use' => 'required|integer|between:1,5',
            'recommend' => 'required|integer|between:1,5',
            'notes' => 'nullable|string|max:2000',
        ]);

        SurveyResponse::updateOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id],
            $data
        );

        return redirect()->route('levels.show', $level)->with('status', 'شكراً لك — وصلنا رأيك.');
    }

    private function hasPassed($user, Level $level): bool
    {
        return $user->examAttempts()
            ->where('level_id', $level->id)
            ->where('passed', true)
            ->exists();
    }
}
