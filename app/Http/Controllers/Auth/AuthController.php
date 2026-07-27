<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referrer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route(Auth::user()->homeRoute()));
    }

    public function showRegister(Request $request): View
    {
        $refCode = $request->query('ref') ?: $request->cookie('restrack_ref');
        $referrer = $refCode ? Referrer::where('referral_code', $refCode)->where('is_active', true)->first() : null;

        return view('auth.register', [
            'referrer' => $referrer,
            'refCode' => $referrer?->referral_code,
            'referrers' => Referrer::active()->get(['id', 'name']),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Referral attribution: a manual pick from the directory wins; else the invite-link code.
        $referrerId = null;
        if ($request->filled('referrer_id')) {
            $referrerId = Referrer::where('id', $request->input('referrer_id'))->where('is_active', true)->value('id');
        }
        if (! $referrerId) {
            $refCode = $request->input('ref') ?: $request->cookie('restrack_ref');
            $referrerId = $refCode ? Referrer::where('referral_code', $refCode)->value('id') : null;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => User::ROLE_STUDENT,
            'locale' => 'ar',
            'referrer_id' => $referrerId,
        ]);

        Cookie::queue(Cookie::forget('restrack_ref'));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
