<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Question;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'levels' => Level::count(),
            'lectures' => Lecture::count(),
            'questions' => Question::count(),
            'active_subs' => Subscription::where('status', 'active')->count(),
            'certificates' => Certificate::count(),
        ];

        $recentUsers = User::latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
