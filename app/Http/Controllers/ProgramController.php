<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        $levels = Level::published()
            ->with(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $passedLevelIds = auth()->user()->examAttempts()->where('passed', true)->distinct()->pluck('level_id');

        return view('student.program', compact('levels', 'passedLevelIds'));
    }

    public function show(Level $level): View
    {
        abort_unless($level->is_published, 404);

        $level->load(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')]);

        $user = auth()->user();
        $completedLectureIds = $user->lectureProgress()->where('completed', true)->pluck('lecture_id');
        $bestAttempt = $user->examAttempts()->where('level_id', $level->id)->orderByDesc('score')->first();
        $passed = $user->examAttempts()->where('level_id', $level->id)->where('passed', true)->exists();

        return view('student.level', compact('level', 'completedLectureIds', 'bestAttempt', 'passed'));
    }
}
