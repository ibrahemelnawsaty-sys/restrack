<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\LectureProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class LectureController extends Controller
{
    public function show(Lecture $lecture): View
    {
        abort_unless($lecture->is_published, 404);

        $lecture->load('level');
        $level = $lecture->level;
        $lectures = $level->lectures()->where('is_published', true)->orderBy('sort_order')->get();

        // Short-TTL signed URL — a leaked link dies within minutes.
        $streamUrl = $lecture->video_path
            ? URL::temporarySignedRoute('lectures.stream', now()->addMinutes(5), ['lecture' => $lecture->id])
            : null;

        return view('student.lecture', compact('lecture', 'level', 'lectures', 'streamUrl'));
    }

    public function progress(Request $request, Lecture $lecture): RedirectResponse
    {
        $completed = $request->boolean('completed', true);

        LectureProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'lecture_id' => $lecture->id],
            [
                'completed' => $completed,
                'completed_at' => $completed ? now() : null,
                'seconds_watched' => (int) $request->input('seconds', 0),
            ]
        );

        return back()->with('status', $completed ? 'أُحتسبت المحاضرة كمكتملة.' : 'تم تحديث تقدّمك.');
    }
}
