<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(): View
    {
        // Doctors — instructors AND ambassadors: how many they referred, and how many converted.
        $doctors = User::whereIn('role', [User::ROLE_INSTRUCTOR, User::ROLE_AMBASSADOR])
            ->withCount([
                'referrals',
                'referrals as subscribers_count' => fn ($q) => $q->subscribedActive(),
            ])
            ->orderByDesc('subscribers_count')
            ->orderByDesc('referrals_count')
            ->get();

        $doctors->each->ensureReferralCode();

        $totalReferred = User::whereNotNull('referred_by')->count();

        return view('admin.referrals.index', compact('doctors', 'totalReferred'));
    }
}
