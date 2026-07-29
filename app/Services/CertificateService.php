<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Level;
use App\Models\User;

class CertificateService
{
    /**
     * Issue a level certificate once (idempotent) when the user has passed it.
     */
    public function issueForLevel(User $user, Level $level): ?Certificate
    {
        // The score the learner passed with — their best passing attempt (owner note م10).
        $best = $user->examAttempts()
            ->where('level_id', $level->id)
            ->where('passed', true)
            ->max('score');

        if ($best === null) {
            return null;
        }

        $certificate = Certificate::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id, 'type' => Certificate::TYPE_LEVEL],
            ['score' => $best],
        );

        // A later, better attempt should upgrade the printed score.
        if ((float) $certificate->score < (float) $best) {
            $certificate->update(['score' => $best]);
        }

        return $certificate;
    }

    /**
     * Issue the final certificate once all published levels are passed.
     */
    public function checkFinalCertificate(User $user): ?Certificate
    {
        $publishedLevelIds = Level::published()->pluck('id');

        if ($publishedLevelIds->isEmpty()) {
            return null;
        }

        $passedLevelIds = $user->examAttempts()
            ->where('passed', true)
            ->whereIn('level_id', $publishedLevelIds)
            ->distinct()
            ->pluck('level_id');

        if ($passedLevelIds->count() < $publishedLevelIds->count()) {
            return null;
        }

        // Final certificate carries the average of the best passing score per level.
        $average = $user->examAttempts()
            ->where('passed', true)
            ->whereIn('level_id', $publishedLevelIds)
            ->selectRaw('level_id, MAX(score) as best')
            ->groupBy('level_id')
            ->pluck('best')
            ->avg();

        return Certificate::firstOrCreate(
            ['user_id' => $user->id, 'type' => Certificate::TYPE_FINAL, 'level_id' => null],
            ['score' => $average !== null ? round($average, 2) : null],
        );
    }
}
