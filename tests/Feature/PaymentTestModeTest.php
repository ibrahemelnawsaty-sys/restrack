<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTestModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function checkoutBody(): string
    {
        $user = User::where('email', 'student@restrack.sa')->first();
        $plan = Plan::where('is_active', true)->firstOrFail();

        return $this->actingAs($user)->get(route('checkout.show', $plan))->getContent();
    }

    public function test_test_credentials_are_detected(): void
    {
        config(['services.paymob.public_key' => 'sau_pk_test_abc', 'services.paymob.secret_key' => 'sau_sk_test_abc']);
        $this->assertTrue(app(PaymentService::class)->isTestMode());

        config(['services.paymob.public_key' => 'sau_pk_live_abc', 'services.paymob.secret_key' => 'sau_sk_live_abc']);
        $this->assertFalse(app(PaymentService::class)->isTestMode());
    }

    public function test_checkout_warns_while_test_keys_are_live(): void
    {
        config(['services.paymob.public_key' => 'sau_pk_test_abc', 'services.paymob.secret_key' => 'sau_sk_test_abc']);
        $this->assertStringContainsString('وضع الاختبار', $this->checkoutBody());
    }

    public function test_checkout_shows_no_warning_on_live_keys(): void
    {
        config(['services.paymob.public_key' => 'sau_pk_live_abc', 'services.paymob.secret_key' => 'sau_sk_live_abc']);
        $this->assertStringNotContainsString('وضع الاختبار', $this->checkoutBody());
    }

    public function test_checkout_is_blocked_while_integration_ids_are_missing(): void
    {
        config([
            'services.paymob.public_key' => 'sau_pk_test_abc',
            'services.paymob.secret_key' => 'sau_sk_test_abc',
            'services.paymob.integration_ids' => [],
        ]);
        $this->assertFalse(app(PaymentService::class)->configured());

        // A fresh learner — the seeded student already holds an active subscription,
        // which would short-circuit to the dashboard before the gateway is ever reached.
        $user = User::create([
            'name' => 'Unsubscribed Learner',
            'email' => 'nosub@example.test',
            'password' => 'password123',
            'role' => User::ROLE_STUDENT,
            'locale' => 'ar',
        ]);
        $plan = Plan::where('is_active', true)->firstOrFail();

        // Must bounce to pricing, never grant access.
        $this->actingAs($user)->post(route('checkout.process', $plan))->assertRedirect(route('pricing'));
        $this->assertFalse($user->fresh()->isSubscribed());
    }
}
