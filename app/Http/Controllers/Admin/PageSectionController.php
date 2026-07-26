<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageSectionController extends Controller
{
    public function index(): View
    {
        $sections = PageSection::orderBy('page')->orderBy('section')->orderBy('item_key')->get();

        return view('admin.content', compact('sections'));
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ((array) $request->input('rows', []) as $id => $vals) {
            PageSection::where('id', $id)->update([
                'value_ar' => $vals['value_ar'] ?? null,
                'value_en' => $vals['value_en'] ?? null,
            ]);
        }

        Cache::forget('page_sections');

        return back()->with('status', 'تم حفظ محتوى الصفحات.');
    }
}
