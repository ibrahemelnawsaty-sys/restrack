<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Guideline;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\PageSection;
use App\Models\Plan;
use App\Models\Question;
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
        $instructor->ensureReferralCode();

        Speaker::updateOrCreate(['user_id' => $instructor->id], [
            'name_ar' => 'د. سارة العتيبي',
            'name_en' => 'Dr. Sara Alotaibi',
            'title_ar' => 'استشارية أبحاث طبية',
            'title_en' => 'Medical Research Consultant',
            'sort_order' => 1,
        ]);

        $student = User::updateOrCreate(['email' => 'student@restrack.sa'], [
            'name' => 'عبدالله محمد الأحمدي',
            'role' => User::ROLE_STUDENT,
            'password' => 'password',
            'referred_by' => $instructor->id,
        ]);

        // Give the demo student an active subscription so they can explore content.
        $annual = Plan::where('slug', 'annual')->first();
        Subscription::updateOrCreate(
            ['user_id' => $student->id, 'status' => Subscription::STATUS_ACTIVE],
            [
                'plan_id' => $annual?->id,
                'amount' => 990,
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
        $ambassador->ensureReferralCode();

        User::updateOrCreate(['email' => 'student2@restrack.sa'], [
            'name' => 'نورة سعد القحطاني',
            'role' => User::ROLE_STUDENT,
            'password' => 'password',
            'referred_by' => $ambassador->id,
        ]);
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
        Plan::updateOrCreate(['slug' => 'monthly'], [
            'name_ar' => 'الاشتراك الشهري',
            'name_en' => 'Monthly',
            'price' => 99.00,
            'interval' => 'monthly',
            'features_ar' => ['وصول كامل للمستويات الثلاثة', 'محاضرات مسجّلة + إعادة مشاهدة', 'اختبارات بمحاولات لا محدودة', 'شهادة إكمال عند الإتمام'],
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        Plan::updateOrCreate(['slug' => 'annual'], [
            'name_ar' => 'الاشتراك السنوي',
            'name_en' => 'Annual',
            'price' => 990.00,
            'interval' => 'annual',
            'features_ar' => ['كل مزايا الشهري', 'توفير ما يعادل شهرين تقريباً', 'مختبرات الممارسة التفاعلية', 'أولوية الدعم وتحديثات المحتوى'],
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 2,
        ]);
    }

    private function seedPageSections(): void
    {
        $rows = [
            ['home', 'hero', 'highlight', 'Research Track Platform', 'Research Track Platform'],
            ['home', 'hero', 'subtitle', 'From Beginner to Expert in Medical Research', 'From Beginner to Expert in Medical Research'],
            ['home', 'hero', 'lead', 'منصة عربية فاخرة لإتقان البحث الطبي — اشتراكٌ واحد يفتح المسار كاملاً من المبتدئ إلى الباحث الناشر، بتجربة زجاجية أنيقة وأداءٍ فائق السرعة.', 'A premium Arabic platform to master medical research — one subscription unlocks the whole path.'],
            ['home', 'about', 'text', 'ريستراك منصة تعليمية احترافية تُنمّي مهارات البحث الطبي عبر برامج منظّمة، تقود المتعلّم من المستوى المبتدئ إلى الخبير.', 'Restrack is a professional learning platform that develops medical research skills through structured programs, guiding learners from beginner to expert levels.'],
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
        $faqs = [
            ['هل يمكنني إعادة الاختبار إن لم أنجح؟', 'نعم — المحاولات غير محدودة، وحدّ النجاح 70%. تُطرح أسئلة مختلفة في كل محاولة من بنكٍ متجدّد.'],
            ['هل المحاضرات مسجّلة؟', 'نعم، كل المحاضرات مسجّلة ويمكنك إعادة مشاهدتها في أي وقت ومن أي جهاز، مع الاستئناف من حيث توقّفت.'],
            ['هل الشهادة معتمدة؟', 'نُصدر شهادة إكمال (Certificate of Completion / شهادة إكمال) موثّقة بتحقّق QR. الاعتماد الرسمي قيد الإجراء، ولا ندّعيه قبل اكتماله.'],
            ['كيف تُحمى الفيديوهات؟', 'بثٌّ محميّ داخل المنصة، روابط موقّعة قصيرة الأمد، علامة مائية متحرّكة باسم الطالب، وحدّ للأجهزة المتزامنة — لا روابط خارجية.'],
            ['هل اشتراكٌ واحد يفتح كل المحتوى؟', 'نعم — اشتراكٌ واحد يفتح المسار كاملاً بمستوياته الثلاثة، دون شراءٍ منفصل لكل دورة.'],
        ];

        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(['question_ar' => $q], [
                'answer_ar' => $a,
                'sort_order' => $i + 1,
                'is_published' => true,
            ]);
        }
    }

    private function seedGuidelines(): void
    {
        foreach (['STROBE', 'PRISMA', 'CONSORT', 'GRADE'] as $i => $name) {
            Guideline::updateOrCreate(['name_ar' => $name], [
                'name_en' => $name,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedSeo(): void
    {
        SeoMeta::updateOrCreate(['route' => 'home'], [
            'title_ar' => 'ريستراك — منصة البحث الطبي',
            'title_en' => 'Restrack — Research Track Platform',
            'description_ar' => 'منصة عربية فاخرة لإتقان البحث الطبي من المبتدئ إلى الباحث الناشر.',
            'description_en' => 'A premium Arabic platform to master medical research from beginner to expert.',
            'noindex' => false,
        ]);
    }
}
