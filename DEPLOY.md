# نشر Restrack على Hostinger (استضافة مشتركة · LiteSpeed · MySQL)

> منصة Laravel 12 · عربي RTL · خفيفة وسريعة. هذا الدليل يوصلك من صفر إلى موقع حيّ على `restrack.sa`.

## 1) قبل الرفع (على جهازك)
```bash
composer install       # يثبّت التبعيات إلى vendor/ (لا تُثبَّت على السيرفر)
npm run build          # يبني الأصول إلى public/build (لا تُبنى على السيرفر)
```
ارفع كل المشروع **عدا**: `/.dev-tools` و `/node_modules` (غير مطلوبة على السيرفر).

> ⚠️ **مطلوب لتصدير شهادة PDF.** حزمة `barryvdh/laravel-dompdf` مُضافة في `composer.json`/`composer.lock` لكنها **غير مثبّتة في `vendor/`** — تعذّر تنزيلها من جهاز التطوير الحالي (‏`codeload.github.com` محجوب). شغّل `composer install` من جهاز يصل إلى GitHub، ثم ارفع `vendor/` كاملاً.
>
> **حتى تُثبَّت، الميزة لا تتعطّل:** `CertificatePdfService::available()` يفحص وجود المحرّك؛ فإن غاب يتحوّل الزر تلقائياً إلى طباعة المتصفّح (`window.print()`) عبر ورقة أنماط `@media print`. لا يلزم أي تعديل شيفرة بعد التثبيت — يتبدّل السلوك وحده.
>
> **تنبيه عربي:** dompdf لا يدعم تشكيل الحروف العربية ولا ترتيب الاتجاه، ولذلك تُعالَج النصوص مسبقاً في `CertificatePdfService`. إن ظهرت الحروف مربّعات فارغة بعد التثبيت، فالسبب أن خط DejaVu المرفق لا يحمل نطاق `U+FE70–FEFF` — والحلّ إرفاق خط عربي مفتوح المصدر (Amiri أو Noto Naskh) وتسجيله في مفتاح `'font'` داخل الخدمة.

## 2) جذر الموقع (Document Root)
اجعل نطاق `restrack.sa` يشير إلى مجلد **`/public`** (من hPanel → Websites → إعدادات النطاق).
إن لم يُتَح تغيير الجذر، انقل محتويات `public/` إلى `public_html/` وعدّل المسارين في `public_html/index.php` ليشيرا إلى مجلد المشروع.

### ⚠️ مفتاح «Force HTTPS» في hPanel يمسح سياسة CSP
تفعيل الخيار يكتب في `.htaccess` سطراً على هذه الصورة:
```apache
Header always set Content-Security-Policy "upgrade-insecure-requests"
```
و`mod_headers` ينفّذه **بعد** خروج PHP، فيستبدل سياستنا الكاملة بهذا السطر الواحد — تفقد الموقع `default-src` و`frame-ancestors` وقيد الـnonce كلها، ولا يظهر في السجل أي خطأ. تحقّق:
```bash
curl -sI https://restrack.sa | grep -i content-security-policy   # يجب أن يبدأ بـ default-src 'self'
```
إن ظهر `upgrade-insecure-requests` وحده، احذف سطر `Header always set …` من `.htaccess` (أبقِ قاعدة إعادة التوجيه إلى https). التوجيه نفسه مضمَّن أصلاً في `SecurityHeaders::policy()` على أي تنصيب `APP_URL` فيه `https://`، فلا تخسر شيئاً بحذفه.

## 3) إعداد البيئة

### 🚨 على موقع يعمل بالفعل: لا تستبدل `.env` كاملاً
`.env.hostinger` الموجود في المستودع **خالٍ من `APP_KEY`**. لو نسخته فوق `.env` الحيّ ستمسح مفتاح التشفير، فتتعطّل كل الجلسات المفتوحة ويصبح أي بيان مُشفَّر غير قابل للفكّ.

على موقع يعمل، عدّل السطور المعنيّة فقط:
1. احذف الأسطر الثلاثة `MOYASAR_*` (لم تعد تُقرأ في أي مكان).
2. أضِف كتلة `PAYMOB_*` الخمسة من §7.
3. اترك `APP_KEY` و`DB_*` كما هي.
4. `php artisan optimize` — الإعدادات مُخزَّنة في الكاش ولن تُقرأ المفاتيح الجديدة قبله.

### على تنصيب جديد فقط
انسخ [`.env.hostinger.example`](.env.hostinger.example) إلى `.env`، ثم:
- اكتب `DB_PASSWORD` (كلمة مرور قاعدة بيانات MySQL من hPanel).
- **دوّر كلمة المرور** من hPanel إن كانت قد شُورِكت سابقاً.
- شغّل `php artisan key:generate` (§4) — بدونه لا يقلع التطبيق أصلاً.

## 4) أوامر التنصيب (عبر SSH أو Terminal في hPanel)
```bash
php artisan key:generate
php artisan migrate --force --seed        # ينشئ الجداول ويزرع المستويات والخطط والحسابات التجريبية

# رابط التخزين العام عبر الـshell وليس artisan — لأن Hostinger يعطّل symlink()/exec() في PHP:
ln -sf "$PWD/storage/app/public" "$PWD/public/storage"

php artisan optimize                      # كاش الإعدادات + المسارات + العروض دفعةً واحدة (لا تدمجها كأوامر منفصلة في سطر)
```
> بعد أول تشغيل، **غيّر كلمات مرور الحسابات التجريبية أو احذفها** (super@ / admin@ / instructor@ / student@restrack.sa).

## 5) الكرون (Cron) — لتشغيل الطابور والمهامّ المجدولة
أضِف في hPanel → Cron Jobs مهمّة **كل دقيقة**:
```
php /home/USER/domains/restrack.sa/artisan schedule:run >> /dev/null 2>&1
```
(يستبدل عامل الطابور الدائم غير المتاح على الاستضافة المشتركة.)

## 6) الفيديو المحميّ
- ارفع الفيديوهات إلى `storage/app/protected-videos/` (خارج الويب تماماً).
- في لوحة الإدارة → المحاضرات، اكتب المسار في حقل «مسار الفيديو» (مثال: `beginner/lesson-1.mp4`).
- يُخدَّم عبر رابط موقّع قصير الأمد + إعادة توجيه LiteSpeed الداخلية + علامة مائية باسم الطالب.
- **صدق:** هذه حماية تتبّعية قوية، لا DRM كامل — لا يمكن منع تسجيل الشاشة في المتصفّح. DRM حقيقي يحتاج خدمة خارجية (Bunny/VdoCipher).

## 7) الدفع (Paymob — KSA)
أضِف في `.env` المفاتيح **الخمسة** التي يقرأها الكود فعلياً (`config/services.php`):

```
PAYMOB_BASE_URL=https://ksa.paymob.com
PAYMOB_PUBLIC_KEY=
PAYMOB_SECRET_KEY=
PAYMOB_HMAC=
PAYMOB_INTEGRATION_IDS=      # معرّفات وسائل الدفع المفعّلة (بطاقة / مدى / Apple Pay) مفصولة بفواصل
```
- تجدها في لوحة Paymob → **Settings → Account Info** (المفتاح العام والسرّي و HMAC)، و**Payment Integrations** (رقم كل وسيلة دفع).

### الحالة الآن (2 سبتمبر 2026)
أربعة من الخمسة **مضبوطة بالفعل** في `.env.hostinger` و`.env` المحلي بمفاتيح **الاختبار**:
`PAYMOB_BASE_URL` · `PAYMOB_PUBLIC_KEY` · `PAYMOB_SECRET_KEY` · `PAYMOB_HMAC`.

الناقص وحده: **`PAYMOB_INTEGRATION_IDS`** — سطر فارغ ينتظر قيمتك. حتى يُملأ يبقى `configured()` يساوي `false` والدفع متوقّف.
لجلبه: لوحة Paymob → **Payment Integrations** → انسخ الرقم الرقمي لكل وسيلة تريد تفعيلها، وافصلها بفواصل:
```
PAYMOB_INTEGRATION_IDS=1234567,7654321
```
> ملاحظة: مفتاح **API key** الطويل ليس مطلوباً — الكود يستخدم *Intention API* بالمفتاح السرّي فقط، ولا يقرأ `PAYMOB_API_KEY` إطلاقاً.

### ⚠️ مفاتيح اختبار على موقع معلَن = وصول مجاني
مفاتيح `sau_*_test_` تجعل Paymob يقبل **بطاقات تجريبية**، والويب‑هوك يفعّل الاشتراك فعلياً بعدها. أي شخص يعرف رقم بطاقة تجريبية منشورة يحصل على اشتراك كامل بلا دفع.
- تظهر الآن لافتة «وضع الاختبار» على صفحة الدفع تلقائياً كلما كانت المفاتيح تجريبية (`PaymentService::isTestMode()`).
- **جرّب بها ثم بدّلها بمفاتيح `sau_pk_live_` و`sau_sk_live_` وHMAC الإنتاج قبل الإعلان عن الموقع**، أو أبقِ الموقع غير معلَن أثناء التجربة.
- اضبط رابط الـWebhook في لوحة Paymob على: `https://restrack.sa/webhooks/paymob`
  (نوع **Transaction processed** — وهو المصدر الوحيد المعتمَد لتفعيل الاشتراك، مُتحقَّق بتوقيع **HMAC-SHA512**، ومُستثنى من CSRF في `bootstrap/app.php`).
- بعد أي تعديل على `.env`: `php artisan optimize` (الإعدادات مُخزَّنة في الكاش).

**بصراحة — ماذا يحدث قبل إضافة المفاتيح:** `PaymentService::configured()` تُرجِع `false` (تشترط `PAYMOB_SECRET_KEY` و`PAYMOB_PUBLIC_KEY` و`PAYMOB_INTEGRATION_IDS` معاً)، فيتوقّف الدفع المدفوع عند إشعار «بوابة الدفع (Paymob) غير مكتملة الإعداد بعد» ويُعاد المستخدم إلى صفحة الأسعار — **ولا يُمنَح أي وصول للمحتوى**. يبقى الاشتراك المُنشَأ بحالة `pending`، ويمكن التفعيل يدوياً من لوحة الاشتراكات.
> **مهم:** مفاتيح `MOYASAR_*` القديمة لم تعد تُقرأ في أي مكان بالكود، ولا يوجد مسار `/webhooks/moyasar` — احذفها من `.env` على السيرفر.

## 8) بعد أي تحديث لاحق
```bash
php artisan migrate --force
php artisan optimize
```
لتفريغ الكاش أثناء التطوير: `php artisan optimize:clear`.

## بيانات المؤسسة (للاستخدام الحرفي)
مؤسسة ريستراك للتدريب · الرقم الموحّد **7053567603** · النطاق **restrack.sa**.
