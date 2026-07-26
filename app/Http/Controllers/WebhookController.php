<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    public function moyasar(Request $request, PaymentService $payments): Response
    {
        $payload = $request->all();

        if (! $payments->verifyWebhook($payload)) {
            return response('invalid signature', 403);
        }

        $data = (array) ($payload['data'] ?? []);

        if (($data['status'] ?? null) === 'paid') {
            $payments->activateByPaymentId($data['id'] ?? null);
            $payments->activateByPaymentId($data['invoice_id'] ?? null);
        }

        return response('ok', 200);
    }
}
