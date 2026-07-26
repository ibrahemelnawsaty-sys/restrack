<?php

namespace App\Http\Controllers\Admin;

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
    public function index(Request $request): View
    {
        $levels = Level::orderBy('sort_order')->get();
        $levelId = $request->integer('level') ?: $levels->first()?->id;

        $lectures = Lecture::with('level')
            ->when($levelId, fn ($q) => $q->where('level_id', $levelId))
            ->orderBy('sort_order')
            ->get();

        return view('admin.lectures.index', compact('levels', 'lectures', 'levelId'));
    }

    public function create(Request $request): View
    {
        $lecture = new Lecture(['level_id' => $request->integer('level'), 'is_published' => true]);

        return view('admin.lectures.form', $this->formData($lecture));
    }

    public function edit(Lecture $lecture): View
    {
        return view('admin.lectures.form', $this->formData($lecture));
    }

    public function store(Request $request): RedirectResponse
    {
        $lecture = new Lecture();
        $this->save($request, $lecture);

        return redirect()->route('admin.lectures.index', ['level' => $lecture->level_id])->with('status', 'أُضيفت المحاضرة.');
    }

    public function update(Request $request, Lecture $lecture): RedirectResponse
    {
        $this->save($request, $lecture);

        return redirect()->route('admin.lectures.index', ['level' => $lecture->level_id])->with('status', 'حُفظت المحاضرة.');
    }

    public function destroy(Lecture $lecture): RedirectResponse
    {
        $level = $lecture->level_id;
        $lecture->delete();

        return redirect()->route('admin.lectures.index', ['level' => $level])->with('status', 'حُذفت المحاضرة.');
    }

    /** Reorder how lessons appear to students (drag alternative: up/down). */
    public function move(Lecture $lecture, string $dir): RedirectResponse
    {
        $neighbor = Lecture::where('level_id', $lecture->level_id)
            ->when($dir === 'up',
                fn ($q) => $q->where('sort_order', '<', $lecture->sort_order)->orderByDesc('sort_order'),
                fn ($q) => $q->where('sort_order', '>', $lecture->sort_order)->orderBy('sort_order'))
            ->first();

        if ($neighbor) {
            [$lecture->sort_order, $neighbor->sort_order] = [$neighbor->sort_order, $lecture->sort_order];
            $lecture->save();
            $neighbor->save();
        }

        return redirect()->route('admin.lectures.index', ['level' => $lecture->level_id]);
    }

    private function formData(Lecture $lecture): array
    {
        return [
            'lecture' => $lecture,
            'levels' => Level::orderBy('sort_order')->get(),
            'speakers' => Speaker::orderBy('sort_order')->get(),
        ];
    }

    private function save(Request $request, Lecture $lecture): void
    {
        $data = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'speaker_id' => 'nullable|exists:speakers,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'video_path' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $lecture->fill($data);

        // Video upload → stored on the PRIVATE 'videos' disk (never web-accessible).
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => ['file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska', 'max:1048576'],
            ]);
            if ($lecture->getOriginal('video_path')) {
                Storage::disk('videos')->delete($lecture->getOriginal('video_path'));
            }
            $lecture->video_path = $request->file('video')->store('lectures', 'videos');
        } elseif (blank($request->input('video_path'))) {
            // No new file and no manual path → keep the current video.
            $lecture->video_path = $lecture->getOriginal('video_path');
        }

        $lecture->duration_seconds = (int) $request->input('duration_seconds', 0);
        $lecture->sort_order = (int) $request->input('sort_order', $lecture->sort_order ?? 0);
        $lecture->is_preview = $request->boolean('is_preview');
        $lecture->is_published = $request->boolean('is_published');
        $lecture->save();
    }
}
