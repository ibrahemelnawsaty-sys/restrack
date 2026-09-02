# Restrack — منصة البحث الطبي · Research Track Platform

منصّة تعليمية عربية (RTL) فاخرة لتعليم **البحث الطبي** من المبتدئ إلى الباحث الناشِر — مؤسسة ريستراك للتدريب · [restrack.sa](https://restrack.sa).

A premium, Arabic-first (RTL) e-learning platform for **medical research** — from beginner to publishing researcher.

---

## المزايا الرئيسية

- **سُلّم من 3 مستويات ممتحَنة** (مبتدئ · متوسّط · خبير) — النجاح 70%، **محاولات لا محدودة**، أسئلة عشوائية لكل محاولة من بنك المستوى.
- **اشتراك واحد يفتح كل المحتوى** — دفع عبر **Paymob** + صفحة دفع مسبق + Webhook موقّع ومتحقَّق.
- **شهادات إكمال** بترقيم آمن `RST-YYYY-XXXX` + صفحة تحقّق عامة عبر QR.
- **فيديو مُستضاف ذاتياً** — قرص خاص + روابط موقّعة قصيرة الأمد + علامة مائية باسم الطالب.
- **لوحة تحكّم كاملة** — نصوص الصفحات · المستويات · المحاضرات (+ ترتيب) · الخطط · الأسئلة الشائعة · المستخدمون والأدوار · الاشتراكات.
- **تصميم زجاجي فاخر** (كحلي/ذهبي) · مود ليلي/نهاري · **SEO تلقائي** (JSON-LD · hreflang · sitemap.xml · robots).

## التقنية

Laravel **12** · PHP **8.2+** · Tailwind v4 + Vite · MySQL (إنتاج) / SQLite (تطوير). مُحسّن لاستضافة **Hostinger شيرنج** (LiteSpeed) — بلا عمّال طابور دائمين ولا اعتماديات ثقيلة.

## التشغيل محلياً

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

حسابات تجريبية (كلمة المرور `password`): `admin@restrack.sa` · `student@restrack.sa` (اشتراك فعّال).

## النشر

راجع **[DEPLOY.md](DEPLOY.md)** وقالب البيئة **[.env.hostinger.example](.env.hostinger.example)**. التوثيق الكامل (الخطة · التصميم · المواصفات) في **[docs/](docs/)**.

## الترخيص

مشروع خاصّ بمؤسسة ريستراك للتدريب — جميع الحقوق محفوظة.
