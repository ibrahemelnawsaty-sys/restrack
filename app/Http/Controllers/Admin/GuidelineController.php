<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guideline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuidelineController extends Controller
{
    public function index(): View
    {
        $guidelines = Guideline::orderBy('sort_order')->get()->groupBy('group_key');

        return view('admin.guidelines.index', compact('guidelines'));
    }

    public function create(): View
    {
        return view('admin.guidelines.form', ['guideline' => new Guideline(['is_active' => true, 'group_key' => 'reporting'])]);
    }

    public function edit(Guideline $guideline): View
    {
        return view('admin.guidelines.form', compact('guideline'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Guideline());

        return redirect()->route('admin.guidelines.index')->with('status', 'أُضيف المعيار.');
    }

    public function update(Request $request, Guideline $guideline): RedirectResponse
    {
        $this->save($request, $guideline);

        return redirect()->route('admin.guidelines.index')->with('status', 'حُفظ المعيار.');
    }

    public function destroy(Guideline $guideline): RedirectResponse
    {
        $guideline->delete();

        return back()->with('status', 'حُذف المعيار.');
    }

    private function save(Request $request, Guideline $guideline): void
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'group_key' => 'required|in:'.implode(',', Guideline::GROUPS),
            'note_ar' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'logo_file' => 'nullable|image|max:1024',
        ]);

        $guideline->fill($data);
        $guideline->sort_order = (int) $request->input('sort_order', $guideline->sort_order ?? 0);
        $guideline->is_active = $request->boolean('is_active');

        if ($request->hasFile('logo_file')) {
            $guideline->logo = $request->file('logo_file')->store('guidelines', 'public');
        }

        $guideline->save();
    }
}
