<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression: the owner pasted the plan bullets from the deck and the live site showed a welded,
 * duplicated bullet ("… revisit anytime • Advanced)"). The paste carries "•"/"·" separators and
 * soft wraps instead of real newlines, and the old splitter only cut on newlines.
 */
class AdminPlanFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function admin(): User
    {
        return User::where('email', 'admin@restrack.sa')->firstOrFail();
    }

    /** @return array<string, string> */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name_ar' => 'خطة اختبار',
            'slug' => 'paste-test',
            'price' => '899',
            'interval' => 'annual',
            'sort_order' => '9',
            'is_active' => '1',
        ], $overrides);
    }

    /** A single pasted line carrying "A • B • C" must become three bullets, not one. */
    public function test_bullet_separators_in_one_pasted_line_become_separate_features(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/plans', $this->payload([
                'features_ar' => 'الميزة الأولى • الميزة الثانية • الميزة الثالثة',
            ]))->assertRedirect(route('admin.plans.index'));

        $this->assertSame([
            'الميزة الأولى',
            'الميزة الثانية',
            'الميزة الثالثة',
        ], Plan::where('slug', 'paste-test')->firstOrFail()->features_ar);
    }

    /** The owner's actual failing paste — two features welded onto one soft-wrapped line. */
    public function test_the_owners_welded_paste_is_split_back_into_its_two_features(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/plans', $this->payload([
                'features_en' => 'Full access to three levels (Foundation • intermediate • Advanced) • Recorded lectures you can watch and revisit anytime',
            ]))->assertRedirect(route('admin.plans.index'));

        // Two features — and the separators *inside* the brackets stay put, because
        // "(Foundation • intermediate • Advanced)" is one bullet, not three.
        $this->assertSame([
            'Full access to three levels (Foundation • intermediate • Advanced)',
            'Recorded lectures you can watch and revisit anytime',
        ], Plan::where('slug', 'paste-test')->firstOrFail()->features_en);
    }

    /** The seeded Arabic bullet keeps its bracketed "·" separators intact. */
    public function test_a_middle_dot_inside_brackets_does_not_split_the_feature(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/plans', $this->payload([
                'features_ar' => "وصول كامل للمستويات الثلاثة (تأسيسي · متوسط · متقدّم)\nمحاضرات مسجّلة تُشاهَد وتُعاد في أي وقت",
            ]))->assertRedirect(route('admin.plans.index'));

        $this->assertSame([
            'وصول كامل للمستويات الثلاثة (تأسيسي · متوسط · متقدّم)',
            'محاضرات مسجّلة تُشاهَد وتُعاد في أي وقت',
        ], Plan::where('slug', 'paste-test')->firstOrFail()->features_ar);
    }

    /** features_en was unreachable from the admin UI — it must now round-trip through the form. */
    public function test_english_features_are_editable_from_the_admin_form_and_shown_in_english(): void
    {
        $plan = Plan::where('slug', 'track-1')->firstOrFail();

        // the blank create form offers the field too (a new plan has no features yet)
        $this->actingAs($this->admin())->get('/admin/plans/create')
            ->assertOk()->assertSee('name="features_en"', false);

        $this->actingAs($this->admin())
            ->put('/admin/plans/'.$plan->id, $this->payload([
                'name_ar' => $plan->name_ar,
                'name_en' => $plan->name_en,
                'slug' => $plan->slug,
                'features_ar' => "ميزة عربية\nميزة عربية أخرى",
                'features_en' => "An English feature\nA second English feature",
                'is_featured' => '1',
            ]))->assertRedirect(route('admin.plans.index'));

        $plan->refresh();
        $this->assertSame(['An English feature', 'A second English feature'], $plan->features_en);

        // the edit form renders what was saved, so the admin can edit it again
        $this->actingAs($this->admin())->get('/admin/plans/'.$plan->id.'/edit')
            ->assertOk()->assertSee('A second English feature', false);

        // and the locale accessor the public views use picks the English list up
        $this->assertSame(['ميزة عربية', 'ميزة عربية أخرى'], $plan->features);
        app()->setLocale('en');
        $this->assertSame(['An English feature', 'A second English feature'], $plan->fresh()->features);
    }

    /** Empty fragments from a trailing separator or a blank line are dropped, not stored. */
    public function test_blank_lines_and_trailing_separators_are_dropped(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/plans', $this->payload([
                'features_ar' => "• ميزة أولى •\n\n   \n• ميزة ثانية",
            ]))->assertRedirect(route('admin.plans.index'));

        $this->assertSame(
            ['ميزة أولى', 'ميزة ثانية'],
            Plan::where('slug', 'paste-test')->firstOrFail()->features_ar
        );
    }
}
