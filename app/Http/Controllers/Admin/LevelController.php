<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        $levels = Level::withCount('lectures', 'questions')->orderBy('sort_order')->get();

        return view('admin.levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('admin.levels.form', ['level' => new Level(['pass_threshold' => 70, 'exam_questions_count' => 5, 'is_published' => true])]);
    }

    public function edit(Level $level): View
    {
        return view('admin.levels.form', compact('level'));
    }

    public function store(Request $request): RedirectResponse
    {
        $level = new Level();
        $this->save($request, $level);

        return redirect()->route('admin.levels.index')->with('status', 'أُضيف المستوى.');
    }

    public function update(Request $request, Level $level): RedirectResponse
    {
        $this->save($request, $level);

        return redirect()->route('admin.levels.index')->with('status', 'حُفظ المستوى.');
    }

    public function destroy(Level $level): RedirectResponse
    {
        $level->delete();

        return back()->with('status', 'حُذف المستوى.');
    }

    private function save(Request $request, Level $level): void
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:120', Rule::unique('levels', 'slug')->ignore($level->id)],
            'focus_ar' => 'nullable|string|max:255',
            'focus_en' => 'nullable|string|max:255',
            'topics_ar' => 'nullable|string',
            'pass_threshold' => 'required|integer|min:1|max:100',
            'exam_questions_count' => 'required|integer|min:1|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $level->fill($data);
        $level->topics_ar = $this->lines($request->input('topics_ar'));
        $level->sort_order = (int) $request->input('sort_order', $level->sort_order ?? 0);
        $level->is_published = $request->boolean('is_published');
        $level->save();
    }

    private function lines(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();
    }
}
