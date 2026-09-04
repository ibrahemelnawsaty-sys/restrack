<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The browser-hardening headers, and — more importantly — proof that the
 * Content-Security-Policy they carry does not silently break a real page.
 */
class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_the_hardening_headers_are_sent_on_a_document(): void
    {
        $response = $this->get('/')->assertOk();

        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
        $this->assertSame('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));

        $permissions = (string) $response->headers->get('Permissions-Policy');
        foreach (['camera=()', 'microphone=()', 'geolocation=()'] as $denied) {
            $this->assertStringContainsString($denied, $permissions);
        }

        $csp = (string) $response->headers->get('Content-Security-Policy');
        foreach ([
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self'",
            "media-src 'self'",
            "connect-src 'self'",
        ] as $directive) {
            $this->assertStringContainsString($directive, $csp, "CSP is missing: {$directive}");
        }

        // form-action keeps 'self' plus the payment gateway: POST /checkout/{plan}
        // redirects to Paymob's hosted page, and browsers check that redirect.
        $this->assertStringContainsString("form-action 'self' https://ksa.paymob.com", $csp);

        // scripts are nonce-gated, never 'unsafe-inline'
        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[A-Za-z0-9]{20,}'/", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $csp);

        // HSTS stays a web-server concern — it must not be sent from PHP
        $this->assertFalse($response->headers->has('Strict-Transport-Security'));
    }

    /** A fresh nonce per response, or the policy is worthless. */
    public function test_the_nonce_changes_on_every_response(): void
    {
        $this->assertNotSame(
            $this->nonceOf($this->get('/')),
            $this->nonceOf($this->get('/')),
        );
    }

    /**
     * The directive borrowed from Hostinger's force-HTTPS .htaccess line, so deleting that
     * line (which was overwriting the whole policy) costs the deployment nothing. It must
     * stay off a plain-http host, where upgrading asset URLs would break every page.
     */
    public function test_insecure_requests_are_upgraded_only_on_an_https_deployment(): void
    {
        config(['app.url' => 'http://localhost']);
        $this->assertStringNotContainsString(
            'upgrade-insecure-requests',
            (string) $this->get('/')->headers->get('Content-Security-Policy')
        );

        config(['app.url' => 'https://restrack.sa']);
        $this->assertStringContainsString(
            'upgrade-insecure-requests',
            (string) $this->get('/')->headers->get('Content-Security-Policy')
        );
    }

    /** Public pages still render, and nothing on them is blocked by the policy. */
    public function test_public_pages_survive_the_policy(): void
    {
        $this->assertPolicyAllows($this->get('/')->assertOk()->assertSee('Research Track Platform'));
        $this->assertPolicyAllows($this->get('/login')->assertOk());
        // the register page carries an inline doctor-picker script
        $this->assertPolicyAllows($this->get('/register')->assertOk()->assertSee('doctorPick', false));
        $this->assertPolicyAllows($this->get('/certificates/verify/does-not-exist')->assertOk());
    }

    /** The lecture player and its watermark — the pages the whole protection story rests on. */
    public function test_the_lecture_player_and_certificate_pages_survive_the_policy(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();
        $lecture = Lecture::where('level_id', $level->id)->firstOrFail();

        $certificate = Certificate::create([
            'user_id' => $student->id,
            'level_id' => $level->id,
            'type' => Certificate::TYPE_LEVEL,
            'score' => 92.5,
        ]);

        $this->actingAs($student);

        $player = $this->get(route('lectures.show', $lecture))->assertOk()
            ->assertSee($lecture->title, false)
            ->assertSee('id="wm"', false)      // the per-student watermark element
            ->assertSee('id="player"', false); // …and the player it sits on
        $this->assertPolicyAllows($player);

        $sheet = $this->get(route('certificates.show', $certificate))->assertOk()
            ->assertSee('CERTIFICATE', false)
            ->assertSee($certificate->number, false);
        $this->assertPolicyAllows($sheet);

        $this->assertPolicyAllows($this->get('/dashboard')->assertOk());
        $this->assertPolicyAllows($this->get(route('levels.show', $level))->assertOk());
    }

    /**
     * The admin/portal screens used to rely on inline on* handlers, which a nonce
     * cannot whitelist. They must now carry the data-* hooks instead.
     */
    public function test_admin_and_portal_screens_survive_the_policy(): void
    {
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin);

        foreach (['levels', 'lectures', 'plans', 'faqs', 'speakers', 'guidelines', 'referrers'] as $section) {
            $page = $this->get('/admin/'.$section)->assertOk();
            $this->assertPolicyAllows($page);
            $this->assertStringContainsString(
                'data-confirm=',
                $page->getContent(),
                "admin/{$section} lost its delete confirmation hook."
            );
        }

        $this->assertStringContainsString('data-autosubmit', $this->get('/admin/lectures')->getContent());

        foreach (['instructor@restrack.sa', 'ambassador@restrack.sa'] as $email) {
            $portal = $this->actingAs(User::where('email', $email)->firstOrFail())
                ->get('/'.explode('@', $email)[0])
                ->assertOk();

            $this->assertPolicyAllows($portal);
            $this->assertStringContainsString('data-copy="#refUrl"', $portal->getContent());
        }
    }

    /** Byte responses (the certificate PDF) get nosniff but no document policy. */
    public function test_binary_responses_are_not_given_a_document_policy(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();

        $certificate = Certificate::create([
            'user_id' => $student->id,
            'level_id' => $level->id,
            'type' => Certificate::TYPE_LEVEL,
            'score' => 80,
        ]);

        $response = $this->actingAs($student)->get(route('certificates.download', $certificate));

        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));

        if (str_contains((string) $response->headers->get('Content-Type'), 'application/pdf')) {
            $this->assertFalse($response->headers->has('Content-Security-Policy'));
        }
    }

    /** The unauthenticated verification endpoint is rate-limited. */
    public function test_the_public_verification_page_is_throttled(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->get('/certificates/verify/does-not-exist')->assertOk();
        }

        $this->get('/certificates/verify/does-not-exist')->assertStatus(429);
    }

    /** Paymob retries a failed callback — the webhook must never be rate-limited. */
    public function test_the_payment_webhook_is_not_throttled(): void
    {
        for ($i = 0; $i < 40; $i++) {
            $this->assertNotSame(429, $this->post('/webhooks/paymob', [])->getStatusCode());
        }
    }

    // ---------------------------------------------------------------- helpers

    private function nonceOf(TestResponse $response): string
    {
        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertMatchesRegularExpression("/'nonce-([A-Za-z0-9]+)'/", $csp, 'response carries no CSP nonce');
        preg_match("/'nonce-([A-Za-z0-9]+)'/", $csp, $matches);

        return $matches[1];
    }

    /**
     * The check a normal assertOk() cannot make: would a browser enforcing this
     * policy actually run/render everything the page ships?
     */
    private function assertPolicyAllows(TestResponse $response): void
    {
        $nonce = $this->nonceOf($response);
        $html = $response->getContent();

        // 1. every executable inline <script> is nonced (ld+json data blocks are not scripts)
        preg_match_all('/<script\b([^>]*)>/i', $html, $tags);
        foreach ($tags[1] as $attributes) {
            if (str_contains($attributes, 'src=') || str_contains($attributes, 'application/ld+json')) {
                continue;
            }
            $this->assertStringContainsString(
                'nonce="'.$nonce.'"',
                $attributes,
                'an inline <script> would be blocked by the CSP: <script'.$attributes.'>'
            );
        }

        // 2. no inline event-handler attributes — a nonce cannot whitelist those
        $this->assertDoesNotMatchRegularExpression(
            '/\son(click|change|submit|load|error|input|keyup|keydown|focus|blur|mouse[a-z]+)\s*=\s*"/i',
            $html,
            'an inline event handler would be blocked by the CSP'
        );

        // 3. every script/stylesheet/image/font/media source is same-origin
        preg_match_all('/<(?:script|img|video|source)\b[^>]*\bsrc="([^"]+)"/i', $html, $sources);
        preg_match_all('/<link\b[^>]*\brel="(?:stylesheet|preload|modulepreload)"[^>]*\bhref="([^"]+)"/i', $html, $links);

        foreach (array_merge($sources[1], $links[1]) as $url) {
            if (! preg_match('#^(https?:)?//#i', $url)) {
                continue; // relative or data: — same origin
            }
            $this->assertStringStartsWith(
                rtrim(config('app.url'), '/'),
                $url,
                "third-party asset would be blocked by default-src 'self': {$url}"
            );
        }
    }
}
