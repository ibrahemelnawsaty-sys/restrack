<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::orderBy('sort_order')->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq(['is_published' => true])]);
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Faq());

        return redirect()->route('admin.faqs.index')->with('status', 'أُضيف السؤال.');
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $this->save($request, $faq);

        return redirect()->route('admin.faqs.index')->with('status', 'حُفظ السؤال.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('status', 'حُذف السؤال.');
    }

    private function save(Request $request, Faq $faq): void
    {
        $data = $request->validate([
            'question_ar' => 'required|string',
            'answer_ar' => 'required|string',
            'question_en' => 'nullable|string',
            'answer_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $faq->fill($data);
        $faq->sort_order = (int) $request->input('sort_order', $faq->sort_order ?? 0);
        $faq->is_published = $request->boolean('is_published');
        $faq->save();
    }
}
