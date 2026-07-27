<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referrer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferrerController extends Controller
{
    public function index(): View
    {
        $referrers = Referrer::withCount([
            'referredUsers',
            'referredUsers as subscribers_count' => fn ($q) => $q->subscribedActive(),
        ])
            ->orderByDesc('subscribers_count')
            ->orderByDesc('referred_users_count')
            ->orderBy('name')
            ->get();

        $total = User::whereNotNull('referrer_id')->count();

        return view('admin.referrers.index', compact('referrers', 'total'));
    }

    /** Add a name-only doctor (no user account) to the directory. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        Referrer::create(['name' => $data['name'], 'is_active' => true])->ensureCode();

        return back()->with('status', 'أُضيف الدكتور إلى القائمة.');
    }

    public function update(Request $request, Referrer $referrer): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        $referrer->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'تم التحديث.');
    }

    public function destroy(Referrer $referrer): RedirectResponse
    {
        // Referred students keep their history but lose the link (FK set null).
        $referrer->delete();

        return back()->with('status', 'حُذف من القائمة.');
    }
}
