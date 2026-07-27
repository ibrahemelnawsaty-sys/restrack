<?php

namespace App\Http\Controllers\Ambassador;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $profile = $user->ensureReferrerProfile();

        $students = $profile->referredUsers()
            ->with(['subscriptions' => fn ($q) => $q->latest()])
            ->latest()
            ->get();

        $referral = [
            'url' => $profile->referralUrl(),
            'registered' => $students->count(),
            'subscribers' => $profile->referredUsers()->subscribedActive()->count(),
        ];

        return view('ambassador.dashboard', compact('user', 'students', 'referral'));
    }
}
