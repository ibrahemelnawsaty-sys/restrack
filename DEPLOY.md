# نشر Restrack على Hostinger (استضافة مشتركة · LiteSpeed · MySQL)

> منصة Laravel 12 · عربي RTL · خفيفة وسريعة. هذا الدليل يوصلك من صفر إلى موقع حيّ على `restrack.sa`.

## 1) قبل الرفع (على جهازك)
```bash
npm run build          # يبني الأصول إلى public/build (لا تُبنى على السيرفر)
```
ارفع كل المشروع **عدا**: `/.dev-tools` و `/node_modules` (غير مطلوبة على السيرفر).

## 2) جذر الموقع (Document Root)
اجعل نطاق `restrack.sa` يشير إلى مجلد **`/public`** (من hPanel → Websites → إعدادات النطاق).
إن لم يُتَح تغيير الجذر، انقل محتويات `public/` إلى `public_html/` وعدّل المسارين في `public_html/index.php` ليشيرا إلى مجلد المشروع.

## 3) إعداد البيئة
انسخ [`.env.hostinger.example`](.env.hostinger.example) إلى `.env`، ثم:
- اكتب `DB_PASSWORD` (كلمة مرور قاعدة بيانات MySQL من hPanel).
- **دوّر كلمة المرور** من hPanel إن كانت قد شُورِكت سابقاً.

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

## 7) الدفع (Moyasar)
أضِف في `.env`: `MOYASAR_PUBLIC_KEY` · `MOYASAR_SECRET_KEY` · `MOYASAR_WEBHOOK_SECRET`.
اضبط رابط الـWebhook في لوحة Moyasar على: `https://restrack.sa/webhooks/moyasar`.
حتى تُضاف المفاتيح، لا يُفعَّل أي اشتراك مدفوع تلقائياً (يمكن التفعيل اليدوي من لوحة الاشتراكات).

## 8) بعد أي تحديث لاحق
```bash
php artisan migrate --force
php artisan optimize
```
لتفريغ الكاش أثناء التطوير: `php artisan optimize:clear`.

## بيانات المؤسسة (للاستخدام الحرفي)
مؤسسة ريستراك للتدريب · الرقم الموحّد **7053567603** · النطاق **restrack.sa**.
