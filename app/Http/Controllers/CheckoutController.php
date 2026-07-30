<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /** Authenticated, pre-payment full program-detail page ending with a Pay CTA. */
    public function show(Plan $plan): View
    {
        abort_unless($plan->is_active, 404);

        $levels = Level::published()
            ->with(['lectures' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $alreadySubscribed = auth()->user()->isSubscribed();

        return view('student.checkout', compact('plan', 'levels', 'alreadySubscribed'));
    }

    public function process(Request $request, Plan $plan, PaymentService $payments): RedirectResponse
    {
        abort_unless($plan->is_active, 404);
        $user = $request->user();

        if ($user->isSubscribed()) {
            return redirect()->route('dashboard')->with('status', 'لديك اشتراكٌ فعّال بالفعل.');
        }

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_PENDING,
            'amount' => $plan->price,
        ]);

        $url = $payments->createCheckout(
            $subscription,
            route('webhooks.paymob'),
            route('checkout.callback', $plan),
        );

        if ($url) {
            return redirect()->away($url);
        }

        // Gateway not fully configured yet — never auto-activate a paid plan.
        return redirect()->route('pricing')->with(
            'status',
            'بوابة الدفع (Paymob) غير مكتملة الإعداد بعد. تُضاف المفاتيح ومعرّفات وسائل الدفع في إعدادات السيرفر.'
        );
    }

    /** Return URL from Paymob. The signed webhook remains the authoritative activation. */
    public function callback(Request $request, Plan $plan): RedirectResponse
    {
        if ($request->boolean('success')) {
            return redirect()->route('dashboard')->with('status', 'تم استلام دفعتك — يُفعّل اشتراكك خلال لحظات.');
        }

        return redirect()->route('pricing')->with('status', 'لم تكتمل عملية الدفع. يمكنك المحاولة مرة أخرى.');
    }
}
