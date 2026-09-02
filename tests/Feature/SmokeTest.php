<?php

namespace Tests\Feature;

use App\Models\Lecture;
use App\Models\Level;
use App\Models\Plan;
use App\Models\User;
use App\Services\ExamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('Research Track Platform');
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/pricing')->assertOk();
        $this->get('/sitemap.xml')->assertOk();
        $this->get('/certificates/verify/does-not-exist')->assertOk();
    }

    /** The landing page must carry the owner's deck content — docs/plan/CONTENT-PLAN.md §3. */
    public function test_home_shows_the_deck_content(): void
    {
        $home = $this->get('/')->assertOk();

        // identity: who we are, vision, mission, goals, values, audience
        $home->assertSee('من نحن', false);
        $home->assertSee('رؤيتنا', false);
        $home->assertSee('رسالتنا', false);
        $home->assertSee('بناء منظومة بحثية سعودية مستدامة', false);
        $home->assertSee('النزاهة الأكاديمية', false);
        $home->assertSee('طلاب الطب', false);

        // credibility: the standards, all four groups
        $home->assertSee('PRISMA', false);
        $home->assertSee('NCBE', false);
        $home->assertSee('Declaration of Helsinki', false);
        $home->assertSee('ICMJE', false);

        // the program, its five inclusions, and the speakers section
        $home->assertSee('Research Track 1', false);
        $home->assertSee('شهادة إتمام نهائية للمسار الكامل', false);
        $home->assertSee('متحدثونا', false);

        // one annual plan at 899 SAR, with the no-risk promise stated before the button
        $home->assertSee('899', false);
        $home->assertSee('محاولات الاختبار غير محدودة', false);

        // and none of the old product-brochure copy survives
        $home->assertDontSee('زجاج راقٍ', false);
        $home->assertDontSee('الأرقام توضيحية', false);

        // the owner removed the delivery-model and quality-assurance sections
        $home->assertDontSee('نموذج التعلّم', false);
        $home->assertDontSee('ضمان الجودة', false);
        // …and the speakers intro no longer claims a nationality
        $home->assertDontSee('سعودية في المجال الطبي', false);
    }

    /** Owner note م9: the learner must read "unlimited attempts" before paying. */
    public function test_checkout_states_unlimited_attempts_before_paying(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $plan = Plan::where('slug', 'track-1')->firstOrFail();

        $body = $this->actingAs($student)->get('/checkout/'.$plan->id)->assertOk()->getContent();

        $notice = mb_strpos($body, 'محاولات الاختبار غير محدودة');
        $button = mb_strpos($body, 'ادفع واشترك');

        $this->assertNotFalse($notice, 'صفحة الدفع لا تذكر أن المحاولات غير محدودة.');
        $this->assertNotFalse($button);
        $this->assertLessThan($button, $notice, 'التوكيد يجب أن يسبق زر الدفع، لا أن يليه.');
    }

    public function test_student_area_renders(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();
        $lecture = Lecture::where('level_id', $level->id)->firstOrFail();
        $plan = Plan::firstOrFail();

        $this->actingAs($student);
        $this->get('/dashboard')->assertOk();
        $this->get('/program')->assertOk();
        $this->get('/levels/beginner')->assertOk();
        $this->get('/lectures/'.$lecture->id)->assertOk();
        $this->get('/levels/beginner/exam')->assertOk();
        $this->get('/certificates')->assertOk();
        $this->get('/checkout/'.$plan->id)->assertOk();
    }

    public function test_exam_flow_passes_and_issues_certificate(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();
        $this->actingAs($student);

        $svc = app(ExamService::class);
        $attempt = $svc->start($student, $level);

        $answers = [];
        foreach ($svc->questionsFor($attempt) as $q) {
            $answers[$q->id] = $q->correct_index;
        }

        $this->post('/levels/beginner/exam', ['attempt_id' => $attempt->id, 'answers' => $answers])
            ->assertRedirect();

        $attempt->refresh();
        $this->assertTrue($attempt->passed);
        $this->assertSame(100, $attempt->score);
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id, 'level_id' => $level->id, 'type' => 'level',
        ]);

        // owner note م10: the certificate carries the score it was earned with
        $certificate = \App\Models\Certificate::where('user_id', $student->id)
            ->where('level_id', $level->id)->firstOrFail();
        $this->assertEquals(100, (float) $certificate->score);
        $this->get(route('certificates.show', $certificate))->assertOk()->assertSee('بدرجة', false);
        $this->get(route('certificates.verify', $certificate->verify_uuid))->assertOk()->assertSee('بدرجة', false);

        $this->get('/exam-attempts/'.$attempt->id)->assertOk();
    }

    /** Owner note م12 — the post-level survey feeds the Quality Assurance claim. */
    public function test_survey_opens_only_after_passing_and_stores_once(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();
        $this->actingAs($student);

        // locked before the level is passed
        $this->get(route('survey.show', $level))->assertRedirect(route('levels.show', $level));

        $svc = app(ExamService::class);
        $attempt = $svc->start($student, $level);
        $answers = [];
        foreach ($svc->questionsFor($attempt) as $q) {
            $answers[$q->id] = $q->correct_index;
        }
        $svc->grade($attempt, $answers);

        $this->get(route('survey.show', $level))->assertOk()->assertSee('جودة شرح المتحدث', false);

        $payload = [
            'content_quality' => 5, 'clarity' => 4, 'speaker_quality' => 5,
            'technical_quality' => 4, 'ease_of_use' => 5, 'recommend' => 5,
            'notes' => 'محتوى ممتاز.',
        ];
        $this->post(route('survey.store', $level), $payload)->assertRedirect(route('levels.show', $level));

        $this->assertDatabaseHas('survey_responses', [
            'user_id' => $student->id, 'level_id' => $level->id, 'content_quality' => 5,
        ]);

        // re-submitting updates the same row rather than creating a second one
        $this->post(route('survey.store', $level), array_merge($payload, ['content_quality' => 3]))->assertRedirect();
        $this->assertSame(1, \App\Models\SurveyResponse::where('user_id', $student->id)->where('level_id', $level->id)->count());
        $this->assertSame(3, (int) \App\Models\SurveyResponse::where('user_id', $student->id)->where('level_id', $level->id)->value('content_quality'));

        // the admin sees the aggregate
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin)->get('/admin/surveys')->assertOk()->assertSee('محتوى ممتاز.', false);
    }

    public function test_admin_manages_speakers_and_guidelines(): void
    {
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin);

        $this->post('/admin/speakers', [
            'name_ar' => 'د. خالد',
            'credential_ar' => 'استشاري أمراض وراثية',
            'highlight_ar' => 'ناشر أكثر من 150 ورقة علمية',
            'is_active' => '1',
        ])->assertRedirect(route('admin.speakers.index'));

        $this->assertDatabaseHas('speakers', ['name_ar' => 'د. خالد', 'is_active' => true]);
        $this->get('/')->assertOk()->assertSee('ناشر أكثر من 150 ورقة علمية', false);

        // a guideline must belong to one of the deck's four groups
        $this->post('/admin/guidelines', ['name_ar' => 'SPIRIT', 'group_key' => 'nope'])
            ->assertSessionHasErrors('group_key');

        $this->post('/admin/guidelines', ['name_ar' => 'SPIRIT', 'group_key' => 'reporting', 'is_active' => '1'])
            ->assertRedirect(route('admin.guidelines.index'));
        $this->get('/')->assertOk()->assertSee('SPIRIT', false);
    }

    public function test_admin_area_renders_for_admin(): void
    {
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin);

        foreach ([
            '/admin', '/admin/content', '/admin/levels', '/admin/levels/create',
            '/admin/lectures', '/admin/plans', '/admin/faqs', '/admin/users', '/admin/subscriptions',
            '/admin/speakers', '/admin/speakers/create',
            '/admin/guidelines', '/admin/guidelines/create',
            '/admin/surveys',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_student_is_forbidden_from_admin(): void
    {
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $this->actingAs($student)->get('/admin')->assertForbidden();
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_upload_lecture_video(): void
    {
        \Illuminate\Support\Facades\Storage::fake('videos');

        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $level = Level::where('slug', 'beginner')->firstOrFail();
        $file = \Illuminate\Http\UploadedFile::fake()->create('lesson.mp4', 400, 'video/mp4');

        $this->actingAs($admin)->post('/admin/lectures', [
            'level_id' => $level->id,
            'title_ar' => 'محاضرة مرفوعة',
            'duration_seconds' => 120,
            'sort_order' => 99,
            'is_published' => '1',
            'video' => $file,
        ])->assertRedirect();

        $lecture = \App\Models\Lecture::where('title_ar', 'محاضرة مرفوعة')->firstOrFail();
        $this->assertNotNull($lecture->video_path);
        \Illuminate\Support\Facades\Storage::disk('videos')->assertExists($lecture->video_path);
    }

    public function test_instructor_has_own_portal_and_scoping(): void
    {
        $instructor = User::where('email', 'instructor@restrack.sa')->firstOrFail();

        // login sends an instructor to their portal (not the student dashboard)
        $this->post('/login', ['email' => 'instructor@restrack.sa', 'password' => 'password'])
            ->assertRedirect(route('instructor.dashboard'));

        $this->actingAs($instructor)->get('/instructor')->assertOk();
        $this->actingAs($instructor)->get('/instructor/lectures')->assertOk();

        // hitting the student dashboard bounces an instructor to their own
        $this->actingAs($instructor)->get('/dashboard')->assertRedirect(route('instructor.dashboard'));

        // instructor cannot reach the admin area
        $this->actingAs($instructor)->get('/admin')->assertForbidden();

        // creating a lecture is force-scoped to the instructor's own speaker
        $this->actingAs($instructor)->post('/instructor/lectures', [
            'level_id' => \App\Models\Level::first()->id,
            'title_ar' => 'محاضرة المدرّب',
            'duration_seconds' => 90,
            'sort_order' => 50,
            'is_published' => '1',
        ])->assertRedirect(route('instructor.lectures.index'));
        $this->assertDatabaseHas('lectures', [
            'title_ar' => 'محاضرة المدرّب',
            'speaker_id' => $instructor->speaker->id,
        ]);

        // instructor may NOT edit a lecture that isn't theirs (deny-by-default)
        $unowned = \App\Models\Lecture::create([
            'level_id' => \App\Models\Level::first()->id,
            'speaker_id' => null,
            'title_ar' => 'ليست له',
            'sort_order' => 999,
            'is_published' => true,
        ]);
        $this->actingAs($instructor)->get(route('instructor.lectures.edit', $unowned))->assertForbidden();
    }

    public function test_referral_link_attribution_and_admin_directory(): void
    {
        $doctor = User::where('email', 'instructor@restrack.sa')->firstOrFail();
        $profile = $doctor->ensureReferrerProfile();
        $code = $profile->referral_code;

        // /r/{code} sends the visitor to registration carrying the ref
        $this->get('/r/'.$code)->assertRedirect(route('register', ['ref' => $code]));

        // registering with the ref attributes the new user to the doctor's directory row
        $this->post('/register', [
            'name' => 'طالب محال',
            'email' => 'referred@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'ref' => $code,
        ])->assertRedirect();

        $newUser = User::where('email', 'referred@example.com')->firstOrFail();
        $this->assertEquals($profile->id, $newUser->referrer_id);

        // admin directory lists the doctor
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin)->get('/admin/referrers')->assertOk()->assertSee($doctor->name);
    }

    public function test_admin_adds_account_less_doctor_to_directory(): void
    {
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();

        $this->actingAs($admin)->post('/admin/referrers', ['name' => 'د. بدون حساب'])->assertRedirect();

        $ref = \App\Models\Referrer::where('name', 'د. بدون حساب')->firstOrFail();
        $this->assertNull($ref->user_id);
        $this->assertNotEmpty($ref->referral_code);
    }

    public function test_registration_can_pick_a_doctor_from_the_directory(): void
    {
        // a seeded account-less directory doctor
        $ref = \App\Models\Referrer::whereNull('user_id')->firstOrFail();

        // a guest registers picking that doctor from the searchable list
        $this->post('/register', [
            'name' => 'طالب مختار',
            'email' => 'picked@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'invited' => 'yes',
            'referrer_id' => $ref->id,
        ])->assertRedirect();

        $u = User::where('email', 'picked@example.com')->firstOrFail();
        $this->assertEquals($ref->id, $u->referrer_id);
    }

    public function test_ambassador_portal_is_invite_only(): void
    {
        $amb = User::where('email', 'ambassador@restrack.sa')->firstOrFail();

        // login lands the ambassador on their own portal
        $this->post('/login', ['email' => 'ambassador@restrack.sa', 'password' => 'password'])
            ->assertRedirect(route('ambassador.dashboard'));

        $this->actingAs($amb)->get('/ambassador')->assertOk();

        // invite-only: no teaching, no admin
        $this->actingAs($amb)->get('/instructor')->assertForbidden();
        $this->actingAs($amb)->get('/admin')->assertForbidden();

        // appears in the admin directory
        $this->assertNotEmpty($amb->ensureReferrerProfile()->referral_code);
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin)->get('/admin/referrers')->assertOk()->assertSee($amb->name);
    }

    public function test_language_switch(): void
    {
        $this->get('/lang/en')->assertRedirect()->assertSessionHas('locale', 'en');

        // a logged-in user's language preference is persisted
        $student = User::where('email', 'student@restrack.sa')->firstOrFail();
        $this->actingAs($student)->get('/lang/ar');
        $this->assertSame('ar', $student->fresh()->locale);

        // an invalid locale falls back to Arabic
        $this->get('/lang/zz')->assertSessionHas('locale', 'ar');
    }

    public function test_paymob_webhook_activates_subscription_only_with_a_valid_hmac(): void
    {
        config(['services.paymob.hmac' => 'test_hmac_secret']);

        $user = User::create([
            'name' => 'دافع تجريبي', 'email' => 'payer@example.com',
            'password' => 'password123', 'role' => User::ROLE_STUDENT,
        ]);
        $plan = Plan::firstOrFail();
        $sub = \App\Models\Subscription::create([
            'user_id' => $user->id, 'plan_id' => $plan->id,
            'status' => \App\Models\Subscription::STATUS_PENDING, 'amount' => $plan->price,
        ]);

        $obj = [
            'amount_cents' => (int) round(((float) $plan->price) * 100),
            'created_at' => '2026-07-30T10:00:00',
            'currency' => 'SAR',
            'error_occured' => false, 'has_parent_transaction' => false,
            'id' => 987654, 'integration_id' => 111,
            'is_3d_secure' => true, 'is_auth' => false, 'is_capture' => false, 'is_refunded' => false,
            'is_standalone_payment' => true, 'is_voided' => false,
            'order' => ['id' => 555, 'merchant_order_id' => $sub->payment_id],
            'owner' => 42, 'pending' => false,
            'source_data' => ['pan' => '2345', 'sub_type' => 'MasterCard', 'type' => 'card'],
            'success' => true,
            'payment_key_claims' => ['extra' => ['subscription_id' => $sub->id]],
        ];

        // same canonical order the service hashes
        $concat = implode('', [
            $obj['amount_cents'], $obj['created_at'], $obj['currency'], 'false', 'false',
            $obj['id'], $obj['integration_id'], 'true', 'false', 'false', 'false',
            'true', 'false', $obj['order']['id'], $obj['owner'], 'false',
            '2345', 'MasterCard', 'card', 'true',
        ]);
        $hmac = hash_hmac('sha512', $concat, 'test_hmac_secret');

        // wrong signature → rejected, nothing activated
        $this->postJson('/webhooks/paymob?hmac=deadbeef', ['type' => 'TRANSACTION', 'obj' => $obj])->assertForbidden();
        $this->assertSame('pending', $sub->fresh()->status);

        // valid signature → activated
        $this->postJson('/webhooks/paymob?hmac='.$hmac, ['type' => 'TRANSACTION', 'obj' => $obj])->assertOk();
        $this->assertSame('active', $sub->fresh()->status);
    }
}
