<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $speaker = self::resolveSpeaker();

        $lectures = $speaker->lectures()->with('level')
            ->orderBy('level_id')->orderBy('sort_order')->get();

        $stats = [
            'lectures' => $lectures->count(),
            'published' => $lectures->where('is_published', true)->count(),
            'levels' => $lectures->pluck('level_id')->unique()->count(),
            'minutes' => (int) round($lectures->sum('duration_seconds') / 60),
        ];

        return view('instructor.dashboard', compact('speaker', 'lectures', 'stats'));
    }

    /** Ensure the logged-in instructor has a Speaker profile (self-heals a newly promoted instructor). */
    public static function resolveSpeaker(): Speaker
    {
        $user = auth()->user();

        return $user->speaker ?: Speaker::create([
            'user_id' => $user->id,
            'name_ar' => $user->name,
            'is_active' => true,
        ]);
    }
}
