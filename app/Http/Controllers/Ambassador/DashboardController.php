<?php

namespace App\Http\Controllers\Ambassador;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $user->ensureReferralCode();

        $students = $user->referrals()
            ->with(['subscriptions' => fn ($q) => $q->latest()])
            ->latest()
            ->get();

        $referral = [
            'url' => $user->referralUrl(),
            'registered' => $students->count(),
            'subscribers' => $user->referrals()->subscribedActive()->count(),
        ];

        return view('ambassador.dashboard', compact('user', 'students', 'referral'));
    }
}
