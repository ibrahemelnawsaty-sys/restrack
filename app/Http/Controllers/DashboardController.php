<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $levels = Level::published()
            ->with(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $passedLevelIds = $user->examAttempts()->where('passed', true)->distinct()->pluck('level_id');
        $completedLectureIds = $user->lectureProgress()->where('completed', true)->pluck('lecture_id');
        $certificates = $user->certificates()->with('level')->latest()->get();

        return view('student.dashboard', compact(
            'user', 'levels', 'passedLevelIds', 'completedLectureIds', 'certificates'
        ));
    }
}
