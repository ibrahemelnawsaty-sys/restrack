<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationLocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function register(string $email): void
    {
        $this->post('/register', [
            'name' => 'Test Person',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
    }

    public function test_registering_in_english_keeps_the_account_in_english(): void
    {
        $this->get('/lang/en');
        $this->register('en-signup@example.test');

        $this->assertSame('en', User::where('email', 'en-signup@example.test')->value('locale'));
    }

    public function test_registering_in_arabic_keeps_the_account_in_arabic(): void
    {
        $this->get('/lang/ar');
        $this->register('ar-signup@example.test');

        $this->assertSame('ar', User::where('email', 'ar-signup@example.test')->value('locale'));
    }

    public function test_the_saved_locale_actually_drives_the_dashboard(): void
    {
        $this->get('/lang/en');
        $this->register('en-dash@example.test');

        $user = User::where('email', 'en-dash@example.test')->first();
        $this->assertSame('en', $user->locale);

        $body = $this->actingAs($user)->get('/dashboard')->getContent();
        $this->assertStringContainsString('Welcome,', $body);
    }
}
