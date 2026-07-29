<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /** Switch the interface language (ar/en); remembered in session + on the user's profile. */
    public function switch(string $locale): RedirectResponse
    {
        $locale = in_array($locale, ['ar', 'en'], true) ? $locale : 'ar';

        session(['locale' => $locale]);

        if ($user = auth()->user()) {
            $user->update(['locale' => $locale]);
        }

        return back();
    }
}
