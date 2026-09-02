<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Level;
use App\Models\User;
use App\Services\CertificatePdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** The redesigned certificate sheet and its PDF export. */
class CertificatePdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function certificate(): Certificate
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();

        return Certificate::create([
            'user_id' => $student->id,
            'level_id' => $level->id,
            'type' => Certificate::TYPE_LEVEL,
            'score' => 92.5,
        ]);
    }

    public function test_the_sheet_carries_the_approved_artwork(): void
    {
        $certificate = $this->certificate();

        $this->actingAs($certificate->user)
            ->get(route('certificates.show', $certificate))
            ->assertOk()
            ->assertSee('CERTIFICATE', false)
            ->assertSee('OF COMPLETION', false)
            ->assertSee('شهادة إكمال', false)
            ->assertSee('نشهد بأن', false)
            ->assertSee($certificate->user->name, false)
            ->assertSee($certificate->number, false)
            // the signature is a marked placeholder — a rule plus the job title, never a name
            ->assertSee('مدير التدريب', false)
            ->assertSee('Director of Training', false)
            // and the sheet defines its own logo gradient rather than borrowing the navbar's
            ->assertSee('id="certlg"', false)
            ->assertSee(route('certificates.download', $certificate), false);
    }

    public function test_the_owner_can_export_their_certificate(): void
    {
        $certificate = $this->certificate();

        $this->assertExported($certificate, $certificate->user);
    }

    public function test_an_admin_can_export_any_certificate(): void
    {
        $certificate = $this->certificate();
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();

        $this->assertExported($certificate, $admin);
    }

    /**
     * The export succeeds either as a real PDF, or — where no PDF engine is installed —
     * as a bounce back to the sheet, which then prints itself from the browser.
     */
    private function assertExported(Certificate $certificate, User $user): void
    {
        $response = $this->actingAs($user)->get(route('certificates.download', $certificate));

        if (app(CertificatePdfService::class)->available()) {
            $response->assertOk();
            $this->assertStringContainsString('pdf', strtolower((string) $response->headers->get('content-type')));
            $this->assertStringStartsWith('%PDF', $response->getContent());

            return;
        }

        $response->assertRedirect(route('certificates.show', $certificate));
    }

    public function test_another_student_cannot_export_someone_elses_certificate(): void
    {
        $certificate = $this->certificate();
        $intruder = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->actingAs($intruder)
            ->get(route('certificates.download', $certificate))
            ->assertForbidden();

        // …and the same rule still guards the page it exports
        $this->actingAs($intruder)
            ->get(route('certificates.show', $certificate))
            ->assertForbidden();
    }

    public function test_a_guest_cannot_export_a_certificate(): void
    {
        $certificate = $this->certificate();

        $this->get(route('certificates.download', $certificate))->assertRedirect('/login');
    }

    /**
     * The PDF template is only ever rendered by dompdf, which may not be installed here —
     * so render it directly to prove it and the service's payload have not drifted apart.
     */
    public function test_the_pdf_template_renders_from_the_service_payload(): void
    {
        $certificate = $this->certificate();
        $certificate->load('user', 'level');

        $data = app(CertificatePdfService::class)->data($certificate);
        $html = view('certificates.template', $data)->render();

        $this->assertStringContainsString('CERTIFICATE', $html);
        $this->assertStringContainsString('OF COMPLETION', $html);
        $this->assertStringContainsString($certificate->number, $html);
        $this->assertStringContainsString('Director of Training', $html);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $html); // the seal
        $this->assertStringContainsString('92.5%', $html);
        // Arabic reached the template already shaped, never as raw logical-order letters
        $this->assertStringNotContainsString('شهادة إكمال', $html);
        $this->assertStringContainsString("\u{FEDD}\u{FE8E}\u{FEE4}\u{FEDB}\u{FE87}", $html);
    }

    /**
     * dompdf neither reorders nor shapes Arabic, so the service does both up front.
     * These are the exact glyphs the PDF must receive.
     */
    public function test_arabic_is_shaped_and_reordered_for_the_pdf(): void
    {
        $service = app(CertificatePdfService::class);

        // شهادة إكمال — words reversed, letters joined, each word reversed
        $this->assertSame(
            "\u{FEDD}\u{FE8E}\u{FEE4}\u{FEDB}\u{FE87} \u{FE93}\u{FEA9}\u{FE8E}\u{FEEC}\u{FEB7}",
            $service->shape('شهادة إكمال')
        );

        // الاختبار — the lam+alef pair must collapse into the single U+FEFB ligature
        $this->assertStringContainsString("\u{FEFB}", $service->shape('الاختبار'));

        // Latin is left exactly as it is
        $this->assertSame('Ahmed Ali', $service->shape('Ahmed Ali'));
    }
}
