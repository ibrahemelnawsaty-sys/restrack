<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = Subscription::with('user', 'plan')->latest()->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /** Manual activation (e.g. bank transfer) — unifies with the gateway path. */
    public function activate(Subscription $subscription): RedirectResponse
    {
        $months = $subscription->plan && $subscription->plan->interval === 'annual' ? 12 : 1;

        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'expires_at' => now()->addMonths($months),
        ]);

        return back()->with('status', 'تم تفعيل الاشتراك.');
    }
}
