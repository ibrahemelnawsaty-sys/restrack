<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.form', ['plan' => new Plan(['interval' => 'monthly', 'is_active' => true])]);
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.form', compact('plan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Plan());

        return redirect()->route('admin.plans.index')->with('status', 'أُضيفت الخطة.');
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $this->save($request, $plan);

        return redirect()->route('admin.plans.index')->with('status', 'حُفظت الخطة.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return back()->with('status', 'حُذفت الخطة.');
    }

    private function save(Request $request, Plan $plan): void
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => ['required', 'string', 'max:60', Rule::unique('plans', 'slug')->ignore($plan->id)],
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:monthly,annual',
            'features_ar' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $plan->fill($data);
        $plan->features_ar = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('features_ar')))
            ->map(fn ($l) => trim($l))->filter()->values()->all();
        $plan->sort_order = (int) $request->input('sort_order', $plan->sort_order ?? 0);
        $plan->is_active = $request->boolean('is_active');
        $plan->is_featured = $request->boolean('is_featured');
        $plan->save();
    }
}
