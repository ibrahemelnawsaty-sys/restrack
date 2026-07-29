<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\SurveyResponse;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /** Aggregated learner feedback — the evidence behind the Quality Assurance section. */
    public function index(): View
    {
        $responses = SurveyResponse::with(['user', 'level'])->latest()->get();
        $levels = Level::orderBy('sort_order')->get();

        $axes = array_keys(SurveyResponse::AXES);

        $overall = collect($axes)->mapWithKeys(fn ($a) => [$a => round((float) $responses->avg($a), 2)]);

        $byLevel = $levels->mapWithKeys(function (Level $level) use ($responses, $axes) {
            $rows = $responses->where('level_id', $level->id);

            return [$level->id => [
                'count' => $rows->count(),
                'scores' => collect($axes)->mapWithKeys(fn ($a) => [$a => round((float) $rows->avg($a), 2)]),
            ]];
        });

        return view('admin.surveys.index', compact('responses', 'levels', 'overall', 'byLevel'));
    }
}
