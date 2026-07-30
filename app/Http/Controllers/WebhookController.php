<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    /** Paymob "Transaction processed" callback — HMAC-verified, authoritative activation. */
    public function paymob(Request $request, PaymentService $payments): Response
    {
        $obj = (array) $request->input('obj', []);
        $hmac = $request->query('hmac') ?? $request->input('hmac');

        if (! $payments->verifyWebhook($obj, $hmac)) {
            return response('invalid signature', 403);
        }

        $payments->activateFromTransaction($obj);

        return response('ok', 200);
    }
}
