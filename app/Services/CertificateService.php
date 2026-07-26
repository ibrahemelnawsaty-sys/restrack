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
        $passed = $user->examAttempts()
            ->where('level_id', $level->id)
            ->where('passed', true)
            ->exists();

        if (! $passed) {
            return null;
        }

        return Certificate::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id, 'type' => Certificate::TYPE_LEVEL],
        );
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

        return Certificate::firstOrCreate(
            ['user_id' => $user->id, 'type' => Certificate::TYPE_FINAL, 'level_id' => null],
        );
    }
}
