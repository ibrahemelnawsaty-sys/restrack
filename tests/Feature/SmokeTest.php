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
}
