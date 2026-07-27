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

        $this->get('/exam-attempts/'.$attempt->id)->assertOk();
    }

    public function test_admin_area_renders_for_admin(): void
    {
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin);

        foreach ([
            '/admin', '/admin/content', '/admin/levels', '/admin/levels/create',
            '/admin/lectures', '/admin/plans', '/admin/faqs', '/admin/users', '/admin/subscriptions',
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

    public function test_referral_attribution_and_admin_overview(): void
    {
        $doctor = User::where('email', 'instructor@restrack.sa')->firstOrFail();
        $code = $doctor->ensureReferralCode();

        // /r/{code} sends the visitor to registration carrying the ref
        $this->get('/r/'.$code)->assertRedirect(route('register', ['ref' => $code]));

        // registering with the ref attributes the new user to the doctor
        $this->post('/register', [
            'name' => 'طالب محال',
            'email' => 'referred@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'ref' => $code,
        ])->assertRedirect();

        $newUser = User::where('email', 'referred@example.com')->firstOrFail();
        $this->assertEquals($doctor->id, $newUser->referred_by);

        // admin overview lists the doctor
        $admin = User::where('email', 'admin@restrack.sa')->firstOrFail();
        $this->actingAs($admin)->get('/admin/referrals')->assertOk()->assertSee($doctor->name);
    }
}
