<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private const API = 'https://api.moyasar.com/v1';

    public function configured(): bool
    {
        return filled(config('services.moyasar.secret'));
    }

    /**
     * Create a Moyasar hosted invoice for a pending subscription.
     * Returns the hosted payment URL, or null if the gateway is not configured / failed.
     */
    public function createInvoice(Subscription $subscription, string $callbackUrl): ?string
    {
        if (! $this->configured()) {
            return null;
        }

        $plan = $subscription->plan;

        $response = Http::withBasicAuth(config('services.moyasar.secret'), '')
            ->asForm()
            ->post(self::API.'/invoices', [
                'amount' => (int) round(((float) $subscription->amount) * 100), // halalas
                'currency' => 'SAR',
                'description' => 'اشتراك '.($plan->name_ar ?? 'Restrack'),
                'callback_url' => $callbackUrl,
                'success_url' => $callbackUrl,
                'metadata' => ['subscription_id' => $subscription->id],
            ]);

        if (! $response->successful()) {
            Log::warning('Moyasar invoice create failed', ['status' => $response->status(), 'body' => $response->body()]);

            return null;
        }

        $data = $response->json();
        $subscription->update(['payment_id' => $data['id'] ?? null]);

        return $data['url'] ?? null;
    }

    /**
     * Verify a webhook payload by matching the configured shared secret token.
     */
    public function verifyWebhook(array $payload): bool
    {
        $secret = config('services.moyasar.webhook_secret');

        if (blank($secret)) {
            return false;
        }

        return hash_equals((string) $secret, (string) ($payload['secret_token'] ?? ''));
    }

    /**
     * Activate the subscription referenced by a paid Moyasar invoice/payment id.
     */
    public function activateByPaymentId(?string $paymentId): void
    {
        if (blank($paymentId)) {
            return;
        }

        $subscription = Subscription::where('payment_id', $paymentId)->latest('id')->first();

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
}
