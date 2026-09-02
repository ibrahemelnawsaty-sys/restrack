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
            'features_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $plan->fill($data);
        // Only rewrite a list the request actually carried. An absent field (stale open tab,
        // programmatic POST) must leave the stored bullets alone rather than blank them;
        // an empty submitted textarea still clears, which is the admin's explicit intent.
        if ($request->has('features_ar')) {
            $plan->features_ar = self::splitFeatures($request->input('features_ar'));
        }
        if ($request->has('features_en')) {
            $plan->features_en = self::splitFeatures($request->input('features_en'));
        }
        $plan->sort_order = (int) $request->input('sort_order', $plan->sort_order ?? 0);
        $plan->is_active = $request->boolean('is_active');
        $plan->is_featured = $request->boolean('is_featured');
        $plan->save();
    }

    /**
     * One feature per line — but a paste from the owner's deck arrives soft-wrapped, carrying
     * "•"/"·" separators instead of real newlines, which used to weld several features into one
     * row. So split on newlines AND on those separators, while ignoring a separator that sits
     * inside brackets: "(تأسيسي · متوسط · متقدّم)" is one feature, not three.
     *
     * @return array<int, string>
     */
    private static function splitFeatures(?string $raw): array
    {
        $features = [];

        foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
            $buffer = '';
            $depth = 0;

            foreach (mb_str_split($line) as $char) {
                if (str_contains('([{', $char)) {
                    $depth++;
                } elseif (str_contains(')]}', $char)) {
                    $depth = max(0, $depth - 1);
                } elseif ($depth === 0 && ($char === '•' || $char === '·')) {
                    $features[] = $buffer;
                    $buffer = '';

                    continue;
                }

                $buffer .= $char;
            }

            $features[] = $buffer;
        }

        return collect($features)->map(fn ($f) => trim($f))->filter()->values()->all();
    }
}
