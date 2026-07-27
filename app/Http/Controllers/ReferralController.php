<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    /** /r/{code} — remember the inviting doctor, then send the visitor to registration. */
    public function capture(string $code): RedirectResponse
    {
        $referrer = User::where('referral_code', $code)->first();

        if (! $referrer) {
            return redirect()->route('register');
        }

        // 30-day cookie as a fallback if the visitor browses before registering.
        Cookie::queue('restrack_ref', $referrer->referral_code, 60 * 24 * 30);

        return redirect()
            ->route('register', ['ref' => $referrer->referral_code])
            ->with('status', 'أنت مدعوٌّ من '.$referrer->name.' — أكمل تسجيلك للبدء.');
    }
}
