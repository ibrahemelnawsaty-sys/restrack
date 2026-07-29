<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Guideline;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\PageSection;
use App\Models\Plan;
use App\Models\Question;
use App\Models\Referrer;
use App\Models\SeoMeta;
use App\Models\Speaker;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPlans();       // before users (demo student subscribes to a plan)
        $this->seedUsers();       // creates the instructor Speaker used by levels
        $this->seedLevels();      // + lectures + question bank
        $this->seedPageSections();
        $this->seedFaqs();
        $this->seedGuidelines();
        $this->seedSeo();
    }

    private function seedUsers(): void
    {
        User::updateOrCreate(['email' => 'super@restrack.sa'], [
            'name' => 'المشرف العام',
            'role' => User::ROLE_SUPER_ADMIN,
            'password' => 'password',
        ]);

        User::updateOrCreate(['email' => 'admin@restrack.sa'], [
            'name' => 'مدير المنصة',
            'role' => User::ROLE_ADMIN,
            'password' => 'password',
        ]);

        $instructor = User::updateOrCreate(['email' => 'instructor@restrack.sa'], [
            'name' => 'د. سارة العتيبي',
            'role' => User::ROLE_INSTRUCTOR,
            'password' => 'password',
        ]);
        $instructorRef = $instructor->ensureReferrerProfile();

        // Note: `highlight_ar` (e.g. "ناشر أكثر من 150 ورقة علمية") is deliberately left empty —
        // it is a verifiable factual claim, so the admin fills it with real, sourced numbers only.
        Speaker::updateOrCreate(['user_id' => $instructor->id], [
            'name_ar' => 'د. سارة العتيبي',
            'name_en' => 'Dr. Sara Alotaibi',
            'title_ar' => 'استشارية أبحاث طبية',
            'title_en' => 'Medical Research Consultant',
            'credential_ar' => 'استشارية أبحاث طبية',
            'credential_en' => 'Medical Research Consultant',
            'sort_order' => 1,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $student = User::updateOrCreate(['email' => 'student@restrack.sa'], [
            'name' => 'عبدالله محمد الأحمدي',
            'role' => User::ROLE_STUDENT,
            'password' => 'password',
            'referrer_id' => $instructorRef->id,
        ]);

        // Give the demo student an active subscription so they can explore content.
        $plan = Plan::where('slug', 'track-1')->first();
        Subscription::updateOrCreate(
            ['user_id' => $student->id, 'status' => Subscription::STATUS_ACTIVE],
            [
                'plan_id' => $plan?->id,
                'amount' => $plan?->price ?? 899,
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
            ]
        );

        // Demo ambassador (a doctor who only invites students) + one student referred by them.
        $ambassador = User::updateOrCreate(['email' => 'ambassador@restrack.sa'], [
            'name' => 'د. خالد الشمري',
            'role' => User::ROLE_AMBASSADOR,
            'password' => 'password',
        ]);
        $ambassadorRef = $ambassador->ensureReferrerProfile();

        User::updateOrCreate(['email' => 'student2@restrack.sa'], [
            'name' => 'نورة سعد القحطاني',
            'role' => User::ROLE_STUDENT,
            'password' => 'password',
            'referrer_id' => $ambassadorRef->id,
        ]);

        // Account-less doctors the admin manages — these appear in the registration picker.
        foreach (['د. أحمد الغامدي', 'د. منى الحربي', 'د. سلطان الدوسري'] as $i => $name) {
            Referrer::updateOrCreate(['name' => $name], ['is_active' => true, 'sort_order' => $i + 1])->ensureCode();
        }
    }

    private function seedLevels(): void
    {
        $speaker = Speaker::first();

        $levels = [
            [
                'slug' => 'beginner',
                'name_ar' => 'الباحث المبتدئ',
                'name_en' => 'Beginner Researcher',
                'focus_ar' => 'أساسيات البحث الطبي',
                'focus_en' => 'Foundations of Medical Research',
                'topics_ar' => ['مقدمة في البحث', 'الأخلاقيات وIRB', 'البحث في الأدبيات', 'الأسئلة البحثية', 'أنواع الدراسات', 'أساسيات التوثيق'],
                'topics_en' => ['Introduction to Research', 'Research Ethics & IRB', 'Literature Search', 'Research Questions', 'Study Types', 'Referencing Basics'],
                'outcomes_ar' => ['فهم أساسيات البحث', 'صياغة أسئلة بحثية صحيحة', 'تطبيق مبادئ الأخلاقيات', 'إجراء مراجعات الأدبيات'],
                'outcomes_en' => ['Understand research fundamentals', 'Formulate valid research questions', 'Apply basic ethics principles', 'Conduct literature reviews'],
                'lectures' => [
                    ['ar' => 'مقدمة في البحث الطبي', 'en' => 'Introduction to Medical Research', 'sec' => 504, 'preview' => true],
                    ['ar' => 'أخلاقيات البحث ولجان IRB', 'en' => 'Research Ethics & IRB', 'sec' => 632, 'preview' => false],
                    ['ar' => 'البحث في الأدبيات العلمية', 'en' => 'Searching the Literature', 'sec' => 458, 'preview' => false],
                ],
            ],
            [
                'slug' => 'intermediate',
                'name_ar' => 'الباحث المتوسّط',
                'name_en' => 'Intermediate Researcher',
                'focus_ar' => 'تصميم الدراسات وإدارة البيانات',
                'focus_en' => 'Research Design & Data Management',
                'topics_ar' => ['تصميم الدراسة', 'طرق العيّنات', 'جمع البيانات', 'أساسيات الإحصاء', 'كتابة المقترح', 'إدارة البيانات', 'التحيّز والمُربِكات'],
                'topics_en' => ['Study Design', 'Sampling Methods', 'Data Collection', 'Statistical Basics', 'Proposal Writing', 'Data Management', 'Bias & Confounding'],
                'outcomes_ar' => ['تصميم دراسات منظّمة', 'تطوير مقترحات بحثية', 'إدارة مجموعات البيانات', 'تطبيق الإحصاء الأساسي', 'تقليل التحيّز'],
                'outcomes_en' => ['Design structured studies', 'Develop research proposals', 'Manage datasets', 'Apply basic statistics', 'Minimize research bias'],
                'lectures' => [
                    ['ar' => 'تصميم الدراسات وحجم العيّنة', 'en' => 'Study Design & Sample Size', 'sec' => 665, 'preview' => false],
                    ['ar' => 'أساسيات الإحصاء الطبي', 'en' => 'Biostatistics Basics', 'sec' => 712, 'preview' => false],
                    ['ar' => 'كتابة المقترح البحثي', 'en' => 'Writing a Research Proposal', 'sec' => 540, 'preview' => false],
                ],
            ],
            [
                'slug' => 'expert',
                'name_ar' => 'الباحث الخبير',
                'name_en' => 'Expert Researcher',
                'focus_ar' => 'الكتابة العلمية والنشر',
                'focus_en' => 'Scientific Writing & Publication',
                'topics_ar' => ['كتابة المخطوطة', 'اختيار المجلة', 'مراجعة الأقران', 'المراجعات المنهجية', 'أخلاقيات النشر', 'أثر البحث', 'أساسيات المنح'],
                'topics_en' => ['Manuscript Writing', 'Journal Selection', 'Peer Review Process', 'Systematic Reviews', 'Publication Ethics', 'Research Impact', 'Grant Writing Basics'],
                'outcomes_ar' => ['كتابة أوراق قابلة للنشر', 'التقديم لمجلات مُفهرسة', 'الاستجابة للمُحكّمين', 'إجراء مراجعات منهجية', 'قيادة المشاريع البحثية'],
                'outcomes_en' => ['Write publishable papers', 'Submit to indexed journals', 'Respond to reviewers', 'Conduct systematic reviews', 'Lead research projects'],
                'lectures' => [
                    ['ar' => 'كتابة المخطوطة العلمية', 'en' => 'Writing the Manuscript', 'sec' => 587, 'preview' => false],
                    ['ar' => 'اختيار المجلة وعملية النشر', 'en' => 'Journal Selection & Submission', 'sec' => 494, 'preview' => false],
                    ['ar' => 'المراجعات المنهجية والتحليل البعدي', 'en' => 'Systematic Reviews & Meta-analysis', 'sec' => 803, 'preview' => false],
                ],
            ],
        ];

        foreach ($levels as $i => $data) {
            $level = Level::updateOrCreate(['slug' => $data['slug']], [
                'sort_order' => $i + 1,
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'focus_ar' => $data['focus_ar'],
                'focus_en' => $data['focus_en'],
                'topics_ar' => $data['topics_ar'],
                'topics_en' => $data['topics_en'],
                'outcomes_ar' => $data['outcomes_ar'],
                'outcomes_en' => $data['outcomes_en'],
                'pass_threshold' => 70,
                'exam_questions_count' => 5,
                'is_published' => true,
            ]);

            foreach ($data['lectures'] as $j => $lec) {
                Lecture::updateOrCreate(
                    ['level_id' => $level->id, 'sort_order' => $j + 1],
                    [
                        'speaker_id' => $speaker?->id,
                        'title_ar' => $lec['ar'],
                        'title_en' => $lec['en'],
                        'duration_seconds' => $lec['sec'],
                        'is_preview' => $lec['preview'],
                        'is_published' => true,
                    ]
                );
            }

            $this->seedQuestions($level);
        }
    }

    private function seedQuestions(Level $level): void
    {
        // A small starter bank per level (randomized per attempt in the exam service).
        $banks = [
            'beginner' => [
                ['q' => 'ما الهدف الأساسي من مراجعة الأدبيات قبل بدء البحث؟', 'o' => ['تحديد الفجوة البحثية', 'زيادة عدد المراجع فقط', 'إطالة المقدمة', 'تجنّب أخذ الموافقة الأخلاقية'], 'c' => 0],
                ['q' => 'أي جهة تُعنى بحماية المشاركين في البحث الطبي؟', 'o' => ['لجنة أخلاقيات البحث (IRB)', 'قسم التسويق', 'إدارة المشتريات', 'دار النشر'], 'c' => 0],
                ['q' => 'السؤال البحثي الجيّد يجب أن يكون:', 'o' => ['محدّداً وقابلاً للقياس', 'عامّاً وغامضاً', 'بلا هدف واضح', 'غير قابل للإجابة'], 'c' => 0],
                ['q' => 'أي مما يلي دراسة رصدية؟', 'o' => ['دراسة الأتراب (Cohort)', 'التجربة السريرية العشوائية', 'دراسة مخبرية تداخلية', 'تجربة دوائية مضبوطة'], 'c' => 0],
                ['q' => 'الغرض من التوثيق العلمي (Referencing) هو:', 'o' => ['نسبة الأفكار لأصحابها وتجنّب الانتحال', 'زيادة عدد الصفحات', 'إخفاء المصادر', 'تعقيد النص'], 'c' => 0],
                ['q' => 'الموافقة المستنيرة (Informed Consent) تعني:', 'o' => ['إعلام المشارك وأخذ موافقته الطوعية', 'إجبار المشارك', 'تجاهل رأي المشارك', 'إخفاء مخاطر البحث'], 'c' => 0],
            ],
            'intermediate' => [
                ['q' => 'ما الغرض من حساب حجم العيّنة قبل الدراسة؟', 'o' => ['ضمان قوة إحصائية كافية', 'إطالة مدة الدراسة', 'زيادة التكلفة عمداً', 'لا فائدة منه'], 'c' => 0],
                ['q' => 'أي طريقة أخذ عيّنات تقلّل التحيّز أكثر؟', 'o' => ['العيّنة العشوائية', 'عيّنة الملاءمة', 'عيّنة كرة الثلج', 'الاختيار المتحيّز'], 'c' => 0],
                ['q' => 'المتغيّر المُربِك (Confounder) هو:', 'o' => ['عامل يؤثّر على النتيجة والتعرّض معاً', 'المتغيّر التابع فقط', 'خطأ مطبعي', 'قيمة مفقودة'], 'c' => 0],
                ['q' => 'أي مقياس مناسب لوصف بيانات كمّية طبيعية التوزيع؟', 'o' => ['المتوسّط والانحراف المعياري', 'المنوال فقط', 'النسبة المئوية فقط', 'لا شيء مما سبق'], 'c' => 0],
                ['q' => 'المقترح البحثي الجيّد يتضمّن:', 'o' => ['الأهداف والمنهجية وخطة التحليل', 'العنوان فقط', 'قائمة أسماء فقط', 'صفحة غلاف فقط'], 'c' => 0],
                ['q' => 'إدارة البيانات الجيّدة تشمل:', 'o' => ['التوثيق والنسخ الاحتياطي وأمن البيانات', 'حذف البيانات فوراً', 'مشاركة كلمات المرور', 'تجاهل الخصوصية'], 'c' => 0],
            ],
            'expert' => [
                ['q' => 'ما بنية المخطوطة العلمية الأكثر شيوعاً؟', 'o' => ['IMRaD (مقدمة، طرق، نتائج، مناقشة)', 'عنوان ثم خاتمة فقط', 'مناقشة ثم مقدمة', 'نتائج فقط'], 'c' => 0],
                ['q' => 'عند اختيار المجلة، من المهم مراعاة:', 'o' => ['النطاق ومعامل التأثير والفهرسة', 'لون الغلاف', 'عدد الإعلانات', 'خط الطباعة'], 'c' => 0],
                ['q' => 'ما الغرض من مراجعة الأقران (Peer Review)؟', 'o' => ['تقييم جودة البحث وصحّته', 'تأخير النشر بلا سبب', 'زيادة الرسوم', 'إخفاء النتائج'], 'c' => 0],
                ['q' => 'المراجعة المنهجية (Systematic Review) تتميّز بـ:', 'o' => ['بروتوكول منهجي شامل وقابل للتكرار', 'اختيار عشوائي للمراجع', 'رأي شخصي فقط', 'دراسة واحدة فقط'], 'c' => 0],
                ['q' => 'من أخلاقيات النشر:', 'o' => ['تجنّب الانتحال والنشر المُكرّر', 'شراء الاستشهادات', 'تزوير البيانات', 'إخفاء تضارب المصالح'], 'c' => 0],
                ['q' => 'المجلات المُفترِسة (Predatory) تتّسم عادةً بـ:', 'o' => ['رسوم عالية دون مراجعة حقيقية', 'مراجعة أقران صارمة', 'فهرسة معتبرة', 'شفافية كاملة'], 'c' => 0],
            ],
        ];

        foreach (($banks[$level->slug] ?? []) as $item) {
            Question::updateOrCreate(
                ['level_id' => $level->id, 'question_ar' => $item['q']],
                [
                    'options_ar' => $item['o'],
                    'correct_index' => $item['c'],
                    'is_published' => true,
                ]
            );
        }
    }

    private function seedPlans(): void
    {
        // Owner decision (2026-07-29): ONE annual subscription at 899 SAR for the whole track.
        Plan::updateOrCreate(['slug' => 'track-1'], [
            'name_ar' => 'Research Track 1 — الاشتراك السنوي',
            'name_en' => 'Research Track 1 — Annual',
            'price' => 899.00,
            'interval' => 'annual',
            'features_ar' => [
                'وصول كامل للمستويات الثلاثة (تأسيسي · متوسط · متقدّم)',
                'محاضرات مسجّلة تُشاهَد وتُعاد في أي وقت',
                'اختبار تقييم بعد كل مستوى بمحاولات لا محدودة',
                'شهادة إتمام لكل مستوى تحمل درجتك',
                'شهادة إتمام نهائية للمسار الكامل',
            ],
            'features_en' => [
                'Full access to all three levels (Foundation · Intermediate · Advanced)',
                'Recorded lectures, revisit them anytime',
                'An exam after each level with unlimited attempts',
                'A completion certificate per level, showing your score',
                'A final completion certificate for the whole track',
            ],
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        // The old illustrative 99/990 plans are retired, not deleted (existing subscriptions keep their FK).
        Plan::whereIn('slug', ['monthly', 'annual'])->update(['is_active' => false, 'is_featured' => false]);
    }

    /**
     * Every public string comes from the owner's deck (Presentation - Research Track Platform.pdf).
     * See docs/plan/CONTENT-PLAN.md §3 for the source of each line. All admin-editable at /admin/content.
     */
    private function seedPageSections(): void
    {
        $rows = [
            // ── hero (deck slide 1 + owner note م1) ──────────────────────────────
            ['home', 'hero', 'kicker', 'مؤسسة ريستراك للتدريب', 'Restrack Training Institute'],
            ['home', 'hero', 'highlight', 'Research Track Platform', 'Research Track Platform'],
            ['home', 'hero', 'subtitle', 'From Beginner to Expert in Medical Research', 'From Beginner to Expert in Medical Research'],
            ['home', 'hero', 'lead', 'مبادرة تعليمية سعودية مكرَّسة لبناء أساسٍ متينٍ في البحث العلمي الطبي — نأخذك من المبتدئ إلى الخبير عبر محتوى متخصّص، وتجارب تفاعلية، ونخبةٍ من المتحدثين والمرشدين.', 'A Saudi educational initiative dedicated to building a strong foundation in medical research — from beginner to expert, through specialized content, interactive experiences, and top-tier speakers and mentors.'],
            ['home', 'hero', 'cta_primary', 'ابدأ رحلتك', 'Start your journey'],
            ['home', 'hero', 'cta_secondary', 'تعرّف على البرنامج', 'Explore the program'],
            ['home', 'hero', 'scroll_cue', 'انزل لتتعرّف على البرنامج', 'Scroll to explore the program'],
            ['home', 'hero', 'pill_levels', '3 مستويات متدرّجة', '3 progressive levels'],
            ['home', 'hero', 'pill_attempts', 'محاولات اختبار لا محدودة', 'Unlimited exam attempts'],
            ['home', 'hero', 'pill_cert', 'شهادة لكل مستوى', 'A certificate per level'],

            // ── who we are (slide 2) ────────────────────────────────────────────
            ['home', 'about', 'title', 'من نحن', 'Who We Are?'],
            ['home', 'about', 'text', 'منصة ريستراك (Research Track Platform) مبادرة تعليمية سعودية مكرَّسة لبناء أساسٍ متينٍ في البحث العلمي، وخصوصاً في المجال الطبي. نُمكِّن المتعلّمين من التدرّج من مرحلة المبتدئ إلى مستوى الخبير عبر محتوى متخصّص، وتجارب تفاعلية، ونخبةٍ من المتحدثين والمرشدين.', 'Research Track Platform is a Saudi educational initiative dedicated to building a strong foundation in scientific research, especially in the medical field. We empower learners to grow from beginners into experts through specialized content, interactive experiences, and top-tier speakers and mentors.'],

            // ── vision & mission (slide 5) ──────────────────────────────────────
            ['home', 'vision', 'vision_title', 'رؤيتنا', 'Our Vision'],
            ['home', 'vision', 'vision', 'أن نُلهِم ونُعِدّ الجيل القادم من قادة البحث الطبي عبر تعليمٍ منظَّم ذي أثر.', 'To inspire and develop the next generation of leaders in medical research through structured, impactful education.'],
            ['home', 'vision', 'mission_title', 'رسالتنا', 'Our Mission'],
            ['home', 'vision', 'mission', 'أن نقود المتعلّم لإنجاز بحثٍ طبيٍّ ذي معنى وأثر، بوضوحٍ وهدف.', 'To guide learners to conduct meaningful and impactful medical research with clarity and purpose.'],

            // ── goals (slide 3) ─────────────────────────────────────────────────
            ['home', 'goals', 'title', 'أهدافنا', 'Our Goals'],
            ['home', 'goals', 'g1', 'تمكين المجتمع الطبي من فهم البحث العلمي وتطبيقه', 'Empower the medical community to understand and apply scientific research'],
            ['home', 'goals', 'g2', 'رفع المهارات البحثية لدى الطلاب والمختصين والأطباء', 'Enhance research skills among students, specialists, and doctors'],
            ['home', 'goals', 'g3', 'بناء منظومة بحثية سعودية مستدامة', 'Build a sustainable Saudi research ecosystem'],
            ['home', 'goals', 'g4', 'إتاحة مصادر أكاديمية موثوقة ومحدَّثة', 'Provide access to reliable, up-to-date academic resources'],

            // ── core values (slide 4) ───────────────────────────────────────────
            ['home', 'values', 'title', 'قيمنا', 'Our Core Values'],
            ['home', 'values', 'v1', 'النزاهة الأكاديمية', 'Academic Integrity'],
            ['home', 'values', 'v2', 'الدقة العلمية', 'Scientific Accuracy'],
            ['home', 'values', 'v3', 'التميّز', 'Excellence'],
            ['home', 'values', 'v4', 'التطوير المستمر', 'Continuous Development'],

            // ── target audience (slide 7) ───────────────────────────────────────
            ['home', 'audience', 'title', 'لمن هذه المنصة؟', 'Target Audience'],
            ['home', 'audience', 'intro', 'صُمِّمت المنصة لتخدم شريحةً واسعة من العاملين في المجالين الطبي والصحي، ولتمنح كلَّ فئةٍ منهم التدريب والموارد اللازمة لتطوّرها المهني ونجاحها.', 'Our platform is designed to support a diverse range of individuals in the medical and healthcare fields, giving each of them the training and resources needed for their professional development and success.'],
            ['home', 'audience', 'a1', 'طلاب الطب', 'Medical students'],
            ['home', 'audience', 'a2', 'خرّيجو العلوم الصحية', 'Health science graduates'],
            ['home', 'audience', 'a3', 'أطباء الامتياز', 'Interns'],
            ['home', 'audience', 'a4', 'الأطباء المقيمون', 'Residents'],
            ['home', 'audience', 'a5', 'الباحثون في بداية مسيرتهم', 'Early-career researchers'],

            // ── why choose us (slide 6) ─────────────────────────────────────────
            ['home', 'why', 'title', 'لماذا تختارنا؟', 'Why Choose Us?'],
            ['home', 'why', 'w1_t', 'تعلّم منظَّم', 'Structured Learning'],
            ['home', 'why', 'w1_b', 'برامج خطوة بخطوة متوافقة مع المعايير الدولية.', 'Step-by-step programs aligned with international standards.'],
            ['home', 'why', 'w2_t', 'إرشاد الخبراء', 'Expert Guidance'],
            ['home', 'why', 'w2_b', 'متحدثون ومرشدون من مؤسساتٍ رائدة.', 'Expert speakers and mentors from leading institutions.'],
            ['home', 'why', 'w3_t', 'التزام عالمي', 'Global Compliance'],
            ['home', 'why', 'w3_b', 'مُصمَّم وفق أدلة البحث والأخلاقيات العالمية.', 'Designed in compliance with global research & ethics guidelines.'],
            ['home', 'why', 'w4_t', 'أثر محلي', 'Local Impact'],
            ['home', 'why', 'w4_b', 'محتوى مبنيّ على الأنظمة السعودية ليخدم المنظومة البحثية في المملكة.', "Content built on Saudi regulations to serve the Kingdom's research ecosystem."],

            // ── program (owner notes م5 · م6) ───────────────────────────────────
            ['home', 'program', 'name', 'Research Track 1', 'Research Track 1'],
            ['home', 'program', 'tagline', 'From Beginner to Expert in Medical Research', 'From Beginner to Expert in Medical Research'],
            ['home', 'program', 'about', 'رحلة تعليمية متكاملة تنقل المتعلّم من مرحلة المبتدئ إلى مستوى الخبير في البحث العلمي عبر مسارٍ تدريجيٍّ منظَّم.', 'An integrated learning journey that takes the learner from beginner to expert in scientific research through a structured, progressive track.'],
            ['home', 'program', 'i1', 'ثلاثة مستويات متدرّجة — تأسيسي · متوسط · متقدّم', 'Three progressive levels — Foundation · Intermediate · Advanced'],
            ['home', 'program', 'i2', 'اختبار تقييم بعد كل مستوى بعددٍ لا محدود من المحاولات', 'An assessment exam after each level, with unlimited attempts'],
            ['home', 'program', 'i3', 'شهادة إتمام لكل مرحلة', 'A completion certificate for each level'],
            ['home', 'program', 'i4', 'شهادة إتمام نهائية للمسار الكامل', 'A final completion certificate for the whole track'],
            ['home', 'program', 'i5', 'محاضرات مسجّلة تُشاهَد في أي وقت وتُعاد كما تشاء', 'Recorded lectures you can watch and revisit anytime'],
            ['home', 'program', 'closing', 'يركّز هذا المسار على بناء الأساس العلمي الصحيح، وتنمية التفكير البحثي، وتمكين المتعلّم من تنفيذ بحثٍ متكاملٍ وفق المعايير الدولية.', 'This track focuses on building the right scientific foundation, developing research thinking, and enabling the learner to conduct a complete study to international standards.'],

            // ── guidelines (slides 9–11 · owner note م4) ────────────────────────
            ['home', 'guidelines', 'title', 'المعايير التي نلتزم بها', 'Our Guidelines'],
            ['home', 'guidelines', 'intro', 'نبني محتوانا على المراجع المعتمدة عالمياً ومحلياً في البحث الطبي — لا اجتهاد ولا محتوى غير موثّق.', 'Our content is built on the standards recognized globally and locally in medical research — nothing improvised, nothing unsourced.'],

            // ── speakers (slide 13 · owner note م3) ─────────────────────────────
            ['home', 'speakers', 'title', 'متحدثونا', 'Our Speakers'],
            ['home', 'speakers', 'intro', 'نختار متحدثينا بعناية — كفاءاتٌ وخبراتٌ سعودية في المجال الطبي، ذات سجلٍّ بحثيٍّ ونشرٍ موثّق.', 'We select our speakers carefully — Saudi expertise in the medical field, with a documented research and publication record.'],
            ['home', 'speakers', 'c1', 'اعتماد أكاديمي مُثبَت', 'Proven academic credentials'],
            ['home', 'speakers', 'c2', 'مشاركة بحثية نشطة', 'Active research involvement'],
            ['home', 'speakers', 'c3', 'سجلّ نشرٍ قوي', 'A strong publication record'],

            // ── learning delivery model (slide 12) ──────────────────────────────
            ['home', 'delivery', 'title', 'نموذج التعلّم', 'Learning Delivery Model'],
            ['home', 'delivery', 'body', 'محاضراتٌ مسجّلة عالية الجودة تمنحك مرونة الوصول إلى المادة في أي وقتٍ ومن أي مكان — تتعلّم بالوتيرة التي تناسبك، وتربط المفاهيم النظرية بالتطبيق العملي في تجربةٍ تعليميةٍ متكاملة.', 'High-quality recorded lectures give you the flexibility to access the material anytime, anywhere — learn at your own pace and integrate theory with practice in a complete educational experience.'],

            // ── quality assurance (slide 14) ────────────────────────────────────
            ['home', 'quality', 'title', 'ضمان الجودة', 'Quality Assurance'],
            ['home', 'quality', 'body', 'التزامنا بالتميّز ينعكس في نظام ضمان جودةٍ يشمل لجان مراجعة أكاديمية، وتدقيق المحتوى، وتحديثاتٍ دورية. ونستطلع رأي المتعلّمين ونحلّل مؤشرات الأداء باستمرار لنُبقي المنهج وثيق الصلة وفعّالاً.', 'Our commitment to excellence is reflected in a quality assurance system that includes academic review committees, content validation, and regular updates. We actively seek learner feedback and analyse performance to keep the curriculum relevant and effective.'],
            ['home', 'quality', 'q1', 'لجان مراجعة أكاديمية', 'Academic review committees'],
            ['home', 'quality', 'q2', 'تدقيق المحتوى', 'Content validation'],
            ['home', 'quality', 'q3', 'تحديث دوري', 'Regular updates'],
            ['home', 'quality', 'q4', 'استبيان المتعلّمين', 'Learner feedback surveys'],
            ['home', 'quality', 'q5', 'تحليلات الأداء', 'Performance analytics'],

            // ── pricing (owner notes م7 · م9) ───────────────────────────────────
            ['home', 'pricing', 'title', 'اشتراك سنوي واحد · المسار كامل', 'One annual subscription · the whole track'],
            ['home', 'pricing', 'note_unlimited', 'محاولات الاختبار غير محدودة. حدّ النجاح 70%، وتُطرح أسئلةٌ مختلفة في كل محاولة من بنك أسئلةٍ متجدّد — لن تخسر ما دفعته إن لم تنجح من المرة الأولى.', 'Exam attempts are unlimited. The passing mark is 70%, and each attempt draws different questions from a rotating bank — you will not lose what you paid if you do not pass the first time.'],
            ['home', 'pricing', 'vat_note', 'الأسعار شاملة ضريبة القيمة المضافة 15% مع فاتورة ZATCA.', 'Prices include 15% VAT with a ZATCA-compliant invoice.'],
        ];

        foreach ($rows as [$page, $section, $key, $ar, $en]) {
            PageSection::updateOrCreate(
                ['page' => $page, 'section' => $section, 'item_key' => $key],
                ['value_ar' => $ar, 'value_en' => $en]
            );
        }
    }

    private function seedFaqs(): void
    {
        // Absorbs the objections the old "content protection" section used to answer (CONTENT-PLAN §3.14).
        $faqs = [
            ['هل المحاضرات مباشرة أم مسجّلة؟', 'كل المحاضرات مسجّلة — تشاهدها في أي وقتٍ ومن أي جهاز، وتعيدها كما تشاء، مع الاستئناف من حيث توقّفت.'],
            ['ماذا لو رسبتُ في الاختبار؟', 'لا شيء يُفقَد — المحاولات غير محدودة وحدّ النجاح 70%، وتُطرح أسئلة مختلفة في كل محاولة.'],
            ['هل الأسئلة ثابتة لكل المتقدّمين؟', 'لا — لكل مستوى بنك أسئلة، ويُختار منه عدد من الأسئلة عشوائياً في كل محاولة، فلا تتكرّر التجربة نفسها.'],
            ['ما الذي أحصل عليه بعد كل مستوى؟', 'شهادة إتمام للمستوى تحمل الدرجة التي اجتزتَه بها، وعند إكمال المستويات الثلاثة تُصدَر شهادة إتمام نهائية للمسار الكامل.'],
            ['هل الشهادة قابلة للتحقّق؟', 'نعم — لكل شهادة رقم فريد وصفحة تحقّق عامة عبر QR. الشهادة شهادة إكمال (Certificate of Completion / شهادة إكمال)، والاعتماد الرسمي قيد الإجراء ولا ندّعيه قبل اكتماله.'],
            ['ما المراجع التي يُبنى عليها المحتوى؟', 'الأنظمة السعودية (NCBE · SFDA-GCP · سياسات لجان الأخلاقيات في وزارة الصحة · نظام حماية البيانات PDPL)، وأدلة الكتابة الدولية (CONSORT · STROBE · PRISMA · CARE · ARRIVE)، وأخلاقيات البحث العالمية (هلسنكي · ICH-GCP · CIOMS · بلمونت · إطار منظمة الصحة العالمية)، ومعايير النشر (ICMJE · COPE · WAME · فانكوفر).'],
            ['لمن هذه المنصة؟', 'طلاب الطب، وخرّيجو العلوم الصحية، وأطباء الامتياز، والأطباء المقيمون، والباحثون في بداية مسيرتهم.'],
            ['هل يمكنني تحميل الفيديوهات؟', 'لا — المحتوى داخل المنصة فقط، بروابط موقّتة وعلامة مائية باسمك، حمايةً لحقوق المتحدثين والمؤسسة.'],
            ['هل اشتراكٌ واحد يفتح كل المحتوى؟', 'نعم — اشتراك سنوي واحد يفتح المسار كاملاً بمستوياته الثلاثة، دون شراءٍ منفصل لكل دورة.'],
        ];

        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(['question_ar' => $q], [
                'answer_ar' => $a,
                'sort_order' => $i + 1,
                'is_published' => true,
            ]);
        }

        // Retire superseded questions instead of deleting them (admin can still see/restore them).
        Faq::whereNotIn('question_ar', array_column($faqs, 0))->update(['is_published' => false]);
    }

    /**
     * The 18 standards from the owner's deck (slides 9–11), in four groups.
     * The owner supplies the logos later; until then each renders as a text badge.
     */
    private function seedGuidelines(): void
    {
        $groups = [
            'saudi' => [
                ['NCBE', 'اللجنة الوطنية لأخلاقيات البحث'],
                ['SFDA — GCP', 'الهيئة العامة للغذاء والدواء — الممارسة السريرية الجيدة'],
                ['MOH — IRB Policies', 'وزارة الصحة — سياسات لجان أخلاقيات البحث'],
                ['SDAIA — PDPL', 'سدايا — نظام حماية البيانات الشخصية'],
            ],
            'reporting' => [
                ['CONSORT', 'التجارب المعشّاة ذات الشواهد'],
                ['STROBE', 'الدراسات الرصدية'],
                ['PRISMA', 'المراجعات المنهجية'],
                ['CARE', 'تقارير الحالة'],
                ['ARRIVE', 'أبحاث الحيوان'],
            ],
            'ethics' => [
                ['Declaration of Helsinki', 'إعلان هلسنكي — الجمعية الطبية العالمية'],
                ['ICH — GCP', 'الممارسة السريرية الجيدة'],
                ['CIOMS', 'إرشادات المجلس الدولي للعلوم الطبية'],
                ['Belmont Report', 'تقرير بلمونت'],
                ['WHO Ethics Framework', 'إطار أخلاقيات منظمة الصحة العالمية'],
            ],
            'publication' => [
                ['ICMJE', 'توصيات اللجنة الدولية لمحرّري المجلات الطبية'],
                ['COPE', 'لجنة أخلاقيات النشر'],
                ['WAME', 'الرابطة العالمية لمحرّري المجلات الطبية'],
                ['Vancouver Style', 'نمط فانكوفر للتوثيق'],
            ],
        ];

        // Retire the old placeholder rows that are not part of the deck.
        Guideline::whereIn('name_ar', ['GRADE'])->update(['is_active' => false]);

        $order = 0;
        foreach ($groups as $key => $items) {
            foreach ($items as [$name, $descAr]) {
                Guideline::updateOrCreate(['name_ar' => $name], [
                    'name_en' => $name,
                    'group_key' => $key,
                    'note_ar' => $descAr,
                    'sort_order' => ++$order,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seedSeo(): void
    {
        SeoMeta::updateOrCreate(['route' => 'home'], [
            'title_ar' => 'ريستراك — Research Track Platform · من المبتدئ إلى الخبير في البحث الطبي',
            'title_en' => 'Restrack — Research Track Platform · From Beginner to Expert in Medical Research',
            'description_ar' => 'مبادرة تعليمية سعودية لبناء أساسٍ متينٍ في البحث الطبي — ثلاثة مستويات متدرّجة، اختبارات بمحاولات لا محدودة، وشهادة إتمام لكل مستوى، وفق المعايير السعودية والدولية.',
            'description_en' => 'A Saudi educational initiative building a strong foundation in medical research — three progressive levels, unlimited exam attempts, and a completion certificate per level, aligned with Saudi and international standards.',
            'noindex' => false,
        ]);
    }
}
