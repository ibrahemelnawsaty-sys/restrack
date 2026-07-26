# CLAUDE.md — دستور مشروع Restrack (كيف نعمل)

> **ماذا نبني:** [`docs/plan/MASTER_PLAN.md`](docs/plan/MASTER_PLAN.md) · **العمل الحالي:** [`docs/plan/ROADMAP.md`](docs/plan/ROADMAP.md) · **التصميم:** [`docs/design/DESIGN-SYSTEM.md`](docs/design/DESIGN-SYSTEM.md) · **النشر:** [`DEPLOY.md`](DEPLOY.md).

## 1. ما هو المشروع
منصّة تعليمية **Laravel 12**، عربية RTL أولاً، لتعليم **البحث الطبي** من المبتدئ إلى الباحث الناشر — مؤسسة ريستراك للتدريب (الرقم الموحّد 7053567603، النطاق restrack.sa). تعمل على **Hostinger شيرنج (LiteSpeed · MySQL)**.

## 2. البيئة المحلية
- PHP/Composer محمولان في `/.dev-tools` (مُستثنى من Git). شغّل: `serve.cmd` أو `./.dev-tools/php/php.exe artisan …`.
- التطوير على SQLite؛ الإنتاج على MySQL. الأصول عبر Vite (`npm run build`).

## 3. قواعد العمل (Definition of Done)
- **لا خطوة بلا تحقّق:** بعد أي تعديل منطقي شغّل `php artisan test` — يجب أن تبقى **خضراء**. اختبار الدخان في `tests/Feature/SmokeTest.php` يغطّي الصفحات العامة، منطقة الطالب، تدفّق الاختبار، الإدارة، والأدوار.
- **بعد تعديل Blade/نصوص:** `php artisan view:clear` (قد يبقى المُصرَّف القديم).
- **قيود الاستضافة المشتركة:** لا عمّال طابور دائمون (استخدم الكرون)، لا FFmpeg، لا HLS مشفّر على السيرفر. التفاصيل في `docs` وذاكرة المشروع.

## 4. اصطلاحات الكود
- **MySQL-safe:** تجنّب الكلمات المحجوزة في أسماء الأعمدة (نستخدم `sort_order`، `item_key`)، وأطوال فهارس آمنة.
- **الأدوار:** عمود `users.role` بسيط + `role`/`subscribed` middleware (بلا حزم ثقيلة). ابدأ برفض افتراضي.
- **الاشتراك:** اشتراك واحد فعّال وغير منتهٍ يفتح كل المحتوى (`User::isSubscribed()`).
- **الاختبارات:** 70% نجاح، **محاولات لا محدودة**، أسئلة عشوائية لكل محاولة من بنك المستوى (`ExamService`).
- **الشهادات:** ترقيم آمن `RST-YYYY-XXXX` + `verify_uuid` + صفحة تحقّق عامة (`CertificateService`).
- **النصوص القابلة للتحرير:** `PageSection::text(page, section, key)` (مُخزَّنة مؤقتاً).
- **التصميم:** نظام الزجاج الفاخر في `resources/css/app.css` — كحلي/ذهبي، أيقونات SVG فقط (لا إيموجي)، أرقام لاتينية، ذهبي نادر، حركة خلف `prefers-reduced-motion`، ليلي/نهاري عبر رموز CSS.

## 5. الأمن
- Webhooks مُستثناة من CSRF ومُتحقَّقة بالتوقيع فقط (`bootstrap/app.php`).
- الفيديو خاص + روابط موقّعة قصيرة الأمد + علامة مائية باسم الطالب.
- لا تُدرِج أسراراً في Git؛ `.env` مُستثنى. دوّر الأسرار المُشارَكة.
