<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * One active, non-expired subscription unlocks all content.
     * Admins/instructors always pass (handled in User::isSubscribed()).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isSubscribed()) {
            return redirect()
                ->route('pricing')
                ->with('status', 'يتطلّب هذا المحتوى اشتراكاً فعّالاً.');
        }

        return $next($request);
    }
}
