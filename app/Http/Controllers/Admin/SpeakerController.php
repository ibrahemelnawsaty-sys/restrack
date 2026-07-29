<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpeakerController extends Controller
{
    public function index(): View
    {
        $speakers = Speaker::orderBy('sort_order')->get();

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create(): View
    {
        return view('admin.speakers.form', ['speaker' => new Speaker(['is_active' => true])]);
    }

    public function edit(Speaker $speaker): View
    {
        return view('admin.speakers.form', compact('speaker'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Speaker());

        return redirect()->route('admin.speakers.index')->with('status', 'أُضيف المتحدث.');
    }

    public function update(Request $request, Speaker $speaker): RedirectResponse
    {
        $this->save($request, $speaker);

        return redirect()->route('admin.speakers.index')->with('status', 'حُفظ المتحدث.');
    }

    public function destroy(Speaker $speaker): RedirectResponse
    {
        $speaker->delete();

        return back()->with('status', 'حُذف المتحدث.');
    }

    private function save(Request $request, Speaker $speaker): void
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'credential_ar' => 'nullable|string|max:255',
            'credential_en' => 'nullable|string|max:255',
            // A publication count is a factual claim — keep it short and sourced.
            'highlight_ar' => 'nullable|string|max:255',
            'highlight_en' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'avatar_file' => 'nullable|image|max:2048',
        ]);

        $speaker->fill($data);
        $speaker->sort_order = (int) $request->input('sort_order', $speaker->sort_order ?? 0);
        $speaker->is_active = $request->boolean('is_active');
        $speaker->is_featured = $request->boolean('is_featured');

        if ($request->hasFile('avatar_file')) {
            $speaker->avatar = $request->file('avatar_file')->store('speakers', 'public');
        }

        $speaker->save();
    }
}
