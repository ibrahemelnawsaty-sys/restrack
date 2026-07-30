<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Paymob (KSA) — Unified Checkout via the Intention API.
 * Flow: create intention (secret key) → redirect to Unified Checkout (public key + client_secret)
 *       → Paymob calls the signed webhook (HMAC) which is the authoritative activation.
 */
class PaymentService
{
    private function base(): string
    {
        return rtrim((string) config('services.paymob.base_url', 'https://ksa.paymob.com'), '/');
    }

    public function configured(): bool
    {
        return filled(config('services.paymob.secret_key'))
            && filled(config('services.paymob.public_key'))
            && ! empty(config('services.paymob.integration_ids'));
    }

    /**
     * Create a payment intention for a pending subscription and return the Unified
     * Checkout URL to redirect the learner to. Null if not configured / the call failed.
     */
    public function createCheckout(Subscription $subscription, string $notificationUrl, string $redirectUrl): ?string
    {
        if (! $this->configured()) {
            return null;
        }

        $user = $subscription->user;
        $plan = $subscription->plan;
        $amountCents = (int) round(((float) $subscription->amount) * 100); // halalas
        $reference = 'restrack-'.$subscription->id.'-'.now()->timestamp;
        [$first, $last] = $this->splitName($user->name ?? 'Student');

        $payload = [
            'amount' => $amountCents,
            'currency' => 'SAR',
            'payment_methods' => array_map(fn ($id) => is_numeric($id) ? (int) $id : $id, config('services.paymob.integration_ids')),
            'items' => [[
                'name' => mb_substr((string) ($plan->name_ar ?? 'Restrack'), 0, 50),
                'amount' => $amountCents,
                'quantity' => 1,
            ]],
            'billing_data' => [
                'first_name' => $first,
                'last_name' => $last,
                'email' => $user->email,
                'phone_number' => '+9660000000000',
                'country' => 'SAU',
                'city' => 'NA', 'state' => 'NA', 'street' => 'NA',
                'building' => 'NA', 'floor' => 'NA', 'apartment' => 'NA',
            ],
            'customer' => [
                'first_name' => $first,
                'last_name' => $last,
                'email' => $user->email,
            ],
            'extras' => ['subscription_id' => $subscription->id],
            'special_reference' => $reference,
            'notification_url' => $notificationUrl,
            'redirection_url' => $redirectUrl,
        ];

        // Paymob's Intention API expects "Authorization: Token <secret_key>" (not Bearer).
        $response = Http::withToken(config('services.paymob.secret_key'), 'Token')
            ->acceptJson()
            ->post($this->base().'/v1/intention/', $payload);

        if (! $response->successful()) {
            Log::warning('Paymob intention failed', ['status' => $response->status(), 'body' => $response->body()]);

            return null;
        }

        $clientSecret = $response->json('client_secret');

        if (blank($clientSecret)) {
            Log::warning('Paymob intention returned no client_secret', ['body' => $response->body()]);

            return null;
        }

        $subscription->update(['payment_id' => $reference]);

        return $this->base().'/unifiedcheckout/?publicKey='.urlencode((string) config('services.paymob.public_key')).'&clientSecret='.urlencode((string) $clientSecret);
    }

    /**
     * Verify a Paymob TRANSACTION webhook: recompute the HMAC (SHA-512) over the
     * canonical ordered fields and compare to the value Paymob sent (query ?hmac=).
     */
    public function verifyWebhook(array $obj, ?string $receivedHmac): bool
    {
        $secret = config('services.paymob.hmac');

        if (blank($secret) || blank($receivedHmac)) {
            return false;
        }

        $keys = [
            'amount_cents', 'created_at', 'currency', 'error_occured', 'has_parent_transaction',
            'id', 'integration_id', 'is_3d_secure', 'is_auth', 'is_capture', 'is_refunded',
            'is_standalone_payment', 'is_voided', 'order.id', 'owner', 'pending',
            'source_data.pan', 'source_data.sub_type', 'source_data.type', 'success',
        ];

        $concat = '';
        foreach ($keys as $key) {
            $concat .= $this->stringifyForHmac(data_get($obj, $key));
        }

        return hash_equals(
            hash_hmac('sha512', $concat, (string) $secret),
            strtolower(trim((string) $receivedHmac))
        );
    }

    /** Activate the subscription behind a successful Paymob transaction. */
    public function activateFromTransaction(array $obj): void
    {
        $success = data_get($obj, 'success');
        if ($success !== true && $success !== 'true') {
            return;
        }

        $subscription = null;

        $sid = data_get($obj, 'payment_key_claims.extra.subscription_id');
        if (is_numeric($sid)) {
            $subscription = Subscription::find((int) $sid);
        }

        if (! $subscription && ($ref = data_get($obj, 'order.merchant_order_id'))) {
            $subscription = Subscription::where('payment_id', $ref)->latest('id')->first();
        }

        if (! $subscription || $subscription->status === Subscription::STATUS_ACTIVE) {
            return;
        }

        $months = $subscription->plan && $subscription->plan->interval === 'annual' ? 12 : 1;

        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'expires_at' => now()->addMonths($months),
        ]);
    }

    private function stringifyForHmac(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value === null ? '' : (string) $value;
    }

    /** @return array{0:string,1:string} */
    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/u', trim($name), 2) ?: [];

        return [$parts[0] ?? 'Student', $parts[1] ?? '.'];
    }
}
