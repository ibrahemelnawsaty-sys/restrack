<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Speaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LectureController extends Controller
{
    private function speaker(): Speaker
    {
        return DashboardController::resolveSpeaker();
    }

    public function index(): View
    {
        $speaker = $this->speaker();
        $lectures = $speaker->lectures()->with('level')
            ->orderBy('level_id')->orderBy('sort_order')->get();

        return view('instructor.lectures.index', compact('speaker', 'lectures'));
    }

    public function create(Request $request): View
    {
        $lecture = new Lecture(['level_id' => $request->integer('level'), 'is_published' => true]);

        return view('instructor.lectures.form', $this->formData($lecture));
    }

    public function edit(Lecture $lecture): View
    {
        $this->authorizeOwn($lecture);

        return view('instructor.lectures.form', $this->formData($lecture));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Lecture());

        return redirect()->route('instructor.lectures.index')->with('status', 'أُضيفت المحاضرة.');
    }

    public function update(Request $request, Lecture $lecture): RedirectResponse
    {
        $this->authorizeOwn($lecture);
        $this->save($request, $lecture);

        return redirect()->route('instructor.lectures.index')->with('status', 'حُفظت المحاضرة.');
    }

    /** Deny-by-default: an instructor may only touch lectures that belong to their own speaker profile. */
    private function authorizeOwn(Lecture $lecture): void
    {
        abort_unless($lecture->speaker_id === $this->speaker()->id, 403);
    }

    private function formData(Lecture $lecture): array
    {
        return [
            'lecture' => $lecture,
            'levels' => Level::orderBy('sort_order')->get(),
        ];
    }

    private function save(Request $request, Lecture $lecture): void
    {
        $data = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $lecture->fill($data);
        $lecture->speaker_id = $this->speaker()->id;   // force ownership — never trust input

        // Video upload → PRIVATE 'videos' disk (never web-accessible).
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => ['file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska', 'max:1048576'],
            ]);
            if ($lecture->getOriginal('video_path')) {
                Storage::disk('videos')->delete($lecture->getOriginal('video_path'));
            }
            $lecture->video_path = $request->file('video')->store('lectures', 'videos');
        } elseif ($lecture->exists) {
            $lecture->video_path = $lecture->getOriginal('video_path');  // keep existing
        }

        $lecture->duration_seconds = (int) $request->input('duration_seconds', 0);
        $lecture->sort_order = (int) $request->input('sort_order', $lecture->sort_order ?? 0);
        $lecture->is_published = $request->boolean('is_published');
        $lecture->is_preview = $request->boolean('is_preview');
        $lecture->save();
    }
}
