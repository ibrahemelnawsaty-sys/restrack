# Restrack — Work Order & Roadmap / سجل التعديلات

> This is the **source of truth for WHAT to build**. `CLAUDE.md` governs HOW.
> Every item lists the owner's request, the current code reality (from the codebase map), and the plan.
> Update the status boxes as work lands. Do not delete completed items — mark them ✅ so history is kept.

Status legend: ☐ not started · ◐ in progress · ✅ done · ⚠️ blocked/needs owner input

---

## 0. Brand & program facts (use verbatim)

- **Org:** مؤسسة ريستراك للتدريب — **Research Track Platform** · National unified no. **7053567603**
- **Colors:** Navy `#16264b` · Gold `#af9136` (exact token, see `resources/css/app.css` `@theme`)
- **Track name (a track/program family — more than one may be activated):** **Research Track Programs (1)**
- **Program tagline:** **From Beginner to Expert in Medical Research**
- **Videos:** fully **self-hosted inside the platform** — no external YouTube/Vimeo. (`CLAUDE.md` §7.)

### The 3-level table (rename levels to these — from `Program 2.pdf`)

| Level | Level Name | Focus Area | Main Topics | Learning Outcomes |
|---|---|---|---|---|
| **Level 1** | **Beginner Researcher** | Foundations of Medical Research | Introduction to Research · Research Ethics & IRB · Literature Search · Research Questions · Study Types · Referencing Basics | Understand research fundamentals · Formulate valid research questions · Apply basic ethics principles · Conduct literature reviews |
| **Level 2** | **Intermediate Researcher** | Research Design & Data Management | Study Design · Sampling Methods · Data Collection · Statistical Basics · Proposal Writing · Data Management · Bias & Confounding | Design structured studies · Develop research proposals · Manage datasets · Apply basic statistics · Minimize research bias |
| **Level 3** | **Expert Researcher** | Scientific Writing & Publication | Manuscript Writing · Journal Selection · Peer Review Process · Systematic Reviews · Publication Ethics · Research Impact · Grant Writing Basics | Write publishable papers · Submit to indexed journals · Respond to reviewers · Conduct systematic reviews · Lead research projects |

Each level ends with an **exam**; **exam attempts are UNLIMITED**; finishing all levels earns a **certificate**.

### Exact copy strings (use these words)

| Where | Text |
|---|---|
| Hero — main line (gold, **one line, large**, next to platform logo) | **Research Track Platform** |
| Hero — subtitle (white) — replaces the old "a world…" line | **From Beginner to Expert in Medical Research** |
| About / after "Restrack" (white) | **Restrack is a professional learning platform that develops medical research skills through structured programs, guiding learners from beginner to expert levels** |
| Certificate title (bilingual) | **Certificate of Completion** / **شهادة إكمال** |
| Passing threshold badge | **70% (unlimited exam attempts)** / **٧٠٪ (محاولات لا محدودة للاختبار)** |
| Certificates section | keep the "we issue certificates" message but **remove the word "accredited/معتمد"** (not accredited yet) |
| Add near features | **lectures are recorded** so students can revisit them anytime (comfort — an essential selling point) |

> Hero **background image** = a person in a research center — **owner will provide**. Platform **logo** and **guideline logos** = **owner will provide** (all images are in the owner's presentation; place them together, guideline logos map to each guideline).

---

## 1. Content & copy changes (low risk, do first)

### 1.1 Home hero rework ☐
- Replace the old "Start Your…" heading with **"Research Track Platform"** as a single large **gold** line + platform logo.
- Set the white subtitle to **"From Beginner to Expert in Medical Research"**.
- Background = owner-provided research-center photo (dark overlay for legibility).
- **Reality:** hero title/subtitle/CTA are DB-driven via `PageSection` (page `home`, section `hero`) with lang fallbacks; the gold highlight line uses lang key `hero_highlight` which is **missing from both lang files** (renders raw key today). Fix by setting the `PageSection` row AND adding the lang keys in **both** `lang/ar` and `lang/en`. Files: `resources/views/pages/home.blade.php`, `lang/{ar,en}/general.php`, `PageSection` seed/row.

### 1.2 "Restrack is…" about text ☐
- Set the white about paragraph to the exact string above. Same location as now; DB (`PageSection` about) and/or lang key.

### 1.3 Rename levels to the 3-level table ☐
- Update `levels` rows (`title_ar`/`title_en`, focus area, topics, outcomes) to Beginner/Intermediate/Expert Researcher per the table. Keep the same UI placement. Files: level seed/data + admin, `resources/views/pages/home.blade.php` program section.

### 1.4 Remove "accredited" everywhere ☐
- Remove/replace the word **accredited / معتمد** in: lang keys (`accredited`, `accredited_desc`, `feature_certificates`, `certified_program(_desc)`, `guidelines/international_guidelines`) in **both** locales, the relevant blades, and the FAQ answer in `database/seeders/SampleDataSeeder.php`.
- Keep the "certificates are issued" message.
- **After editing:** `php artisan view:clear` (stale compiled Blade in `storage/framework/views` still contains the old text).

### 1.5 Add "recorded lectures" messaging ☐
- Add a clear line (features/benefits + program detail) that **all lectures are recorded and can be revisited anytime**. Bilingual keys in both locales.

### 1.6 "70% (unlimited exam attempts)" ☐
- Next to the 70% passing badge, append **"(unlimited exam attempts)"** / **"(محاولات لا محدودة)"**. Also reflect in FAQ/questions & answers copy.

### 1.7 Guideline logos ☐
- Render each guideline's owner-provided logo (Guideline model has a `logo`). Ensure `loading="lazy"`, width/height set. Owner supplies the image files.

### 1.8 Track name label ☐
- Surface **"Research Track Programs (1)"** as the program family name wherever the program is titled.

---

## 2. "Read more" → full program detail (pre-purchase) ☐

**Request:** it's hard to pay for something they don't understand. Add **Read more** on the program so visitors see the details **before paying**:
- The **3 levels**, and for each level **what lectures it contains**, stated as **recorded** and **revisitable**.
- After **each level → an exam**; on finishing → a **certificate**.
- Emphasize **exam attempts are UNLIMITED** (removes the "what if I pay and fail" fear).

**Two surfaces:**
1. Public **Read more** expansion/section on the home program area (marketing — reassure before signup).
2. **Post-login, pre-payment full program-detail page** (§4) with **Pay** or **Start** at the end.

**Reality:** program details currently live only as an anchor section on the public home page; there is **no** dedicated detail page. This is net-new view(s) + route(s).

---

## 3. Certificates ☐

**Request:** electronic certificate emailed to the student on course completion; title **"Certificate of Completion / شهادة إكمال"**.

**Reality (map):**
- Certs are **auto-created as DB rows** on exam pass (`ExamService::handlePassed` / `checkFinalCertificate`); a `final` cert once all published levels pass. **Nothing is emailed** (no `app/Mail`, no `Notification`).
- `CertificateService::generatePdf()` renders `certificates.template` — **that Blade file does NOT exist** → download is currently broken.

**Plan:**
- Create `resources/views/certificates/template.blade.php` with the bilingual **Certificate of Completion / شهادة إكمال** title, brand styling, student name, program, level/final, cert number, verify URL/QR, issue date.
- Add a **Mailable/Notification** and hook it into the completion point in `ExamService` to email the PDF (respect `QUEUE_CONNECTION=database` — queue it, don't block the request).
- Guard `Certificate::generateNumber()` against collisions (retry-on-unique).

---

## 4. Payments — Moyasar end-to-end + pre-payment page ☐ ⚠️

**Request:** after register + login, **before paying**, show a **full program-detail page**; end with **Pay** or **Start**. Online payment via gateway.

**Reality (map):**
- Gateway = **Moyasar**. Paid flow is a **stub**: `PaymentService::createPayment()` returns `'#'`; `CheckoutController::process()` only completes free (100%-coupon) checkouts; no pending `Subscription`+`payment_id` is created, yet the webhook looks it up by `payment_id`. **Not end-to-end.**
- Webhook `/webhooks/moyasar` is **unsigned** and has **no real CSRF exemption** in `bootstrap/app.php` (`CLAUDE.md` §6.3).

**Plan:**
- Build the authenticated **program-detail page** (route + view) shown before checkout, ending with Pay/Start CTA.
- Implement Moyasar create-payment → create **pending** `Subscription` with `payment_id` → redirect to gateway → **signed** webhook (`HMAC` verify, amount/currency check) flips to `active` → CSRF-exempt the route. **Unify the two activation paths** (`PaymentService` + `Admin\SubscriptionController`).
- Enforce `expires_at` in `isSubscribed()` (currently ignored).

---

## 5. Teachers (self-upload) + admin ordering ☐

**Request:** each **teacher has a user account** to upload their own lessons; **admin can rename lessons and reorder** how they appear to students.

**Reality (map):**
- `Speaker` is **not** a `User` (has an `email` string, no `user_id`); only `super_admin`/`admin`/`student` roles exist — **no `teacher` role or portal**. Admin renames lectures already (`Admin\LectureController::update`). Reordering is **manual integer entry only** — no drag/reorder endpoint.

**Plan:**
- Add a **`teacher` role** + link teacher `User` ↔ `Speaker` (add `speakers.user_id` or a teacher profile), a **teacher portal** (auth group), and a **teacher-scoped lecture upload** flow (own lectures only — scope every query).
- Admin: keep rename; add a **reorder endpoint/UI** (drag or up/down) writing the `order` column. Admin can reorder/rename how students see lessons.

---

## 6. Exams / question bank ☐

**Request:** a **question bank**; each trainee gets **different questions** than the previous one; **unlimited attempts**.

**Reality (map):** questions are **already randomized per attempt** (`ExamService::generateExam` → `inRandomOrder()->limit(exam_questions_count)`) and there is **already no attempt limit** → both goals largely already hold. Gaps: the pool is per-level (`questions.level_id`), and selected IDs live in the **global session key `exam_questions`** (fragile across tabs/timeout) with strict `===` grading and no cast.

**Plan (mostly hardening, not net-new):**
- Optionally grow/curate the per-level bank; ensure "different questions each attempt" is visible/communicated.
- Fix the session fragility: namespace exam state per level/attempt and/or persist selected question IDs on the `ExamAttempt` row; make grading type-safe (cast/normalize answers).
- Surface **unlimited attempts** in UI + FAQ (see §1.6). Update the "questions & answers" (FAQ) copy accordingly.

---

## 7. Self-hosted video + content protection ☐ (largest, most complex)

**Request:** all videos **inside the platform** (no external), **recorded**, protected — students **can't download or screen-record**; platform stays **fast** for many students.

**Reality + honest limits + target architecture:** see **`CLAUDE.md` §7** in full. Summary:
- Current code = external embeds + a dead `custom` provider with no upload/player. Replace with self-hosted.
- **Cannot** prevent screenshots/screen-recording in a browser — the realistic defense is **dynamic per-student watermarking** (traceability) + short-TTL **signed, entitlement-checked** delivery + **encrypted HLS** segments behind a **CDN** (never stream through PHP-FPM). Background **FFmpeg transcode** on the queue. Private disk only.

---

## 8. Performance hardening ☐

Target: light & fast for many students (`CLAUDE.md` §8). Cache homepage queries (`Cache::remember`), self-host fonts, bundle Alpine (drop CDNs), gate always-on animations behind `prefers-reduced-motion`, lazy images, ship `npm run build` + `config:cache route:cache view:cache`, kill N+1s.

---

## 9. Setup & safety (do before Phase 1) ☐ ⚠️

- `composer install` (dev tooling is missing) · locate/activate PHP 8.2+ CLI · confirm baseline `composer test` runs.
- ⚠️ **Rotate secrets:** `.env` holds real production DB+SMTP credentials that **reuse one password**; owner should rotate and use distinct secrets. Never migrate against the prod DB without `--env=testing`.
- Confirm `APP_LOCALE` intent (config default `en`, but the whole UI is Arabic/RTL).

---

## 10. New scope — from the Master Plan (net-new tactical items) ☐

Full rationale in [`MASTER_PLAN.md`](MASTER_PLAN.md). Net-new work beyond §1–§9 above:

- **10.1 Automatic SEO engine** ☐ — hreflang (ar/en/x-default), auto XML sitemaps + IndexNow/ping, JSON-LD (Organization, WebSite+SearchAction, Course/CourseInstance, BreadcrumbList, FAQPage, VideoObject, Person), OG/Twitter + dynamic OG images, admin SEO cockpit (meta editor, 301 redirects, noindex, health). Elevate existing `SeoMeta`/`SeoService`/`SeoController`. (Master Plan §13.)
- **10.2 Light + dark mode (مود نهاري/ليلي)** ☐ — both themes from `@theme` tokens; persisted per user; honors `prefers-color-scheme`; gold-as-text darkens on light. (Master Plan §14.)
- **10.3 `instructor` role + Policies** ☐ — add role, link User↔Speaker, deny-by-default Policies on Lecture/Level/Course/Exam/Certificate. (Master Plan §5.)
- **10.4 Instructor portal** ☐ — own-content course/level builder, upload→encrypted-HLS, quiz authoring, drip, analytics; admin approval workflow. (Master Plan §4.2.)
- **10.5 Interactive practice labs** ☐ — sample-size calc, t-test, appraise-an-abstract, forest plot, ethics spotting, with instant feedback. (Master Plan §4.1.)
- **10.6 MFA (TOTP)** ☐ — mandatory admin+instructor, optional students; hardened sessions.
- **10.7 PDPL tooling** ☐ — granular consent ledger, self-service export/delete (30d), Arabic privacy notice, 72h breach runbook. (Master Plan §8.)
- **10.8 Payments (KSA)** ☐ — mada + Apple Pay + STC Pay + 3-DS via SAMA-licensed gateway; BNPL (Tabby/Tamara) annual-only; tokenized auto-renew + dunning; **15% VAT + ZATCA e-invoice**. (Extends §4; Master Plan §9.)
- **10.9 WhatsApp-first lifecycle + referrals** ☐ — CST-licensed BSP; welcome/activation/abandoned-checkout/renewal/win-back; consent-safe; referral at certificate-earned. (Master Plan §11.)
- **10.10 Concurrency/device limits + anomaly detection** ☐ — the real anti-sharing lever (Master Plan §7 L6).
- **10.11 Adult gamification** ☐ — streaks (Hijri-aware), XP, mastery rings, metallic badges, opt-in leaderboards. (Master Plan §4.1.)
- **10.12 Audit log + CI security automation** ☐ — tamper-evident log; composer/npm audit, SCA, SAST, WAF/CDN. (Master Plan §8.)
- **10.13 B2B seat-license portal** ☐ — sub-accounts, cohort reporting, bulk enrollment. (Master Plan §4.3.)

---

## 11. Content overhaul — from the owner's presentation ☐ (supersedes §1 copy items)

**Source:** `Presentation - Research Track Platform.pdf` (owner deck, 2026-07-29) — the institutional identity deck, including the owner's handwritten Arabic notes.

**Full blueprint:** [`CONTENT-PLAN.md`](CONTENT-PLAN.md) — gap analysis, single-page IA, ready-to-paste AR/EN copy deck, schema + `PageSection` key tables, phased execution with acceptance criteria, and the owner's open decisions.

Net-new surfaces the deck requires that §1–§10 never covered: **Who We Are · Vision & Mission · Goals · Core Values · Target Audience · Guidelines (18 standards in 4 groups, logo marquee) · Speakers · Learning Delivery Model · Quality Assurance · post-level Survey · certificate score**. Pricing moves to a **single 899 SAR** program.

> ⚠️ **Naming conflict to resolve:** the deck says the three levels are «تأسيسي – متوسط – متقدّم», while §0 above adopts *Beginner / Intermediate / Expert Researcher* from `Program 2.pdf`. Owner decides — see `CONTENT-PLAN.md` §9.

---

## Suggested sequencing (owner decides final order)

1. **§9 setup/safety** → **§1 copy/content** (fast, visible wins, low risk).
2. **§3 certificates** (fix broken template + email).
3. **§6 exam hardening** (small) + **§2 read-more** detail.
4. **§4 payments** end-to-end + pre-payment page.
5. **§5 teachers + reorder.**
6. **§7 self-hosted video + protection** (biggest).
7. **§8 performance** pass throughout and at the end.

Each item ships through the `CLAUDE.md` workflow: PLAN → BUILD → **regression-verifier gate** → REPORT.
