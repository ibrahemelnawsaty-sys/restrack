<?php

namespace App\Services;

use App\Models\ExamAttempt;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Collection;

class ExamService
{
    public function __construct(private CertificateService $certificates) {}

    /**
     * Start an attempt: pick a fresh random set from the level's bank and snapshot the ids
     * onto the attempt row (robust across tabs/timeouts — no fragile session key).
     */
    public function start(User $user, Level $level): ExamAttempt
    {
        $questionIds = Question::where('level_id', $level->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->limit(max(1, $level->exam_questions_count))
            ->pluck('id')
            ->all();

        return ExamAttempt::create([
            'user_id' => $user->id,
            'level_id' => $level->id,
            'question_ids' => $questionIds,
            'started_at' => now(),
        ]);
    }

    /** Load the snapshot questions for an attempt, in the stored order. */
    public function questionsFor(ExamAttempt $attempt): Collection
    {
        $ids = $attempt->question_ids ?? [];
        $byId = Question::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $byId->get($id))->filter()->values();
    }

    /**
     * Grade the attempt against the snapshot. Answers = [question_id => chosen_index].
     * Type-safe comparison; unlimited attempts (no cap anywhere).
     */
    public function grade(ExamAttempt $attempt, array $answers): ExamAttempt
    {
        $questions = $this->questionsFor($attempt);
        $total = max(1, $questions->count());
        $correct = 0;

        $normalized = [];
        foreach ($questions as $q) {
            $chosen = $answers[$q->id] ?? null;
            $chosen = is_null($chosen) ? null : (int) $chosen;
            $normalized[$q->id] = $chosen;
            if ($chosen !== null && $chosen === (int) $q->correct_index) {
                $correct++;
            }
        }

        $score = (int) round($correct / $total * 100);
        $threshold = (int) $attempt->level->pass_threshold;
        $passed = $score >= $threshold;

        $attempt->update([
            'answers' => $normalized,
            'score' => $score,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        if ($passed) {
            $this->certificates->issueForLevel($attempt->user, $attempt->level);
            $this->certificates->checkFinalCertificate($attempt->user);
        }

        return $attempt->refresh();
    }
}
