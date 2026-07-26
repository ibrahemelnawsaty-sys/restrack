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

        $url = $payments->createInvoice($subscription, route('checkout.callback', $plan));

        if ($url) {
            return redirect()->away($url);
        }

        // Gateway not configured yet — never auto-activate a paid plan.
        return redirect()->route('pricing')->with(
            'status',
            'بوابة الدفع Moyasar غير مفعّلة بعد. أضِف مفاتيح الـ API في إعدادات السيرفر لتفعيل الدفع.'
        );
    }

    /** Return URL from Moyasar. The webhook remains the authoritative activation. */
    public function callback(Request $request, Plan $plan, PaymentService $payments): RedirectResponse
    {
        if ($request->query('status') === 'paid') {
            $payments->activateByPaymentId($request->query('id') ?? $request->query('invoice_id'));

            return redirect()->route('dashboard')->with('status', 'تم تفعيل اشتراكك بنجاح — أهلاً بك!');
        }

        return redirect()->route('pricing')->with('status', 'لم تكتمل عملية الدفع. يمكنك المحاولة مرة أخرى.');
    }
}
