# Restrack — Master Plan / الخطة الكبرى للمنصة

> **Strategy, product spec, and design system** for Restrack — the premium, Arabic-first, unmistakably Saudi
> medical-research academy. `CLAUDE.md` governs *how* we build; `ROADMAP.md` (this folder) is the tactical work order;
> **this document is the product source of truth (the *what* and *why*).** Grounded in live 2026 competitor +
> standards research (8-agent study; full findings in the workflow transcript).

---

## ملخص تنفيذي (عربي)

**الرؤية:** نبني *أفخر* منصة عربية لتعليم **البحث الطبي** لطلاب الجامعات السعوديين والأطباء — اشتراك واحد يفتح **سُلّماً كاملاً من المبتدئ إلى الباحث الناشر**: منهجية بحث، إحصاء (SPSS/R)، حجم العينة، الكتابة العلمية، المراجعات المنهجية والتحليل البعدي، GRADE، أخلاقيات/IRB، والنشر — بالعربية مع المصطلحات الإنجليزية.

**لماذا ننتصر:** المنافسون إمّا عرب لكن غير سعوديين (Negida مصري، MedONE بريطاني CPD) أو عالميون بالإنجليزية (Coursera). **نحن الوحيدون** الذين نجمع: **سعودة كاملة** (SCFHS CME، ريال، مدى/Apple Pay/STC Pay/تابي-تمارا، فاتورة ZATCA، استضافة داخل المملكة)، **اشتراك موحّد** يفتح كل شيء، **سُلّم ممتحَن** بشهادات موثّقة، **تعلّم بالممارسة**، **تصميم فاخر** (كحلي/ذهبي RTL)، **فيديو مشفّر ذاتي** بعلامة مائية، وأداء **خفيف وسريع** وأمن عالٍ.

**الجمهور:** طلاب الطب/الصحة والعلوم، أطباء الامتياز والمقيمون، والباحثون المبتدئون — ومؤسسياً: كليات الطب والمستشفيات (تراخيص مقاعد B2B).

---

## 1. Market landscape & how we win

The Arabic medical-research training space is real, growing, and under-served. Demand signal (2024 Saudi study, n=469): **74%** cite inadequate statistical skills, **75.5%** want to do research, **89.6%** see it as important, yet only **66.5%** ever attended a research course. Vision 2030's RDI agenda adds institutional tailwind.

| Competitor | What they are | Their weakness | How Restrack beats them |
|---|---|---|---|
| **Negida Academy** | Closest rival — Arabic, full C1–C7 curriculum, ~30K learners, real PubMed track record | Egyptian (not Saudi), **pay-per-course** ($30–300 each), USD, non-Saudi accreditation, utilitarian UX | Saudi-native (SCFHS CME, SAR, mada/STC Pay, ZATCA), **one all-access subscription**, premium RTL brand |
| **MedONE (Dr. Qunaibi)** | One very popular "Basics of Medical Research" course, British-CPD | **Single basics course**, key-person risk, no ladder/gating | Full beginner→expert **examined ladder**, Saudi SCFHS accreditation, graded progression |
| **Free MOOCs & YouTube** (Rwaq, Edraak, Doroob, YouTubers) | The real substitute for *attention* — free, broad | Unstructured, ungraded, low production, no credential | Sell what free can't: structure, graded exams, recognized certificates, mentorship, publication outcome |
| **Coursera / edX** (Hopkins, Harvard) | Prestigious | **English-only**, culturally un-localized, foreign payment/credentials | Arabic-first, Saudi IRB/ethics/data cases, local rails, career-relevant Saudi certificates |
| **Univ. deanships & SCFHS workshops** (KSU DSR, KAU KFMRC, KSAU-HS, KFMC) | Credible, SCFHS-accredited | In-person, internal-only, unscalable | Scalable premium online product — and convert them into **B2B seat-license buyers** |
| **Virtual Medical Academy / SBPM** | Saudi, CME-adjacent | Generic "basics", dated UX | Focused premium research brand + a real examined competency ladder |
| **Noon Academy** | Arabic-first **UX benchmark** (K-12, not a rival) | Playful, not research | Match its RTL-native quality; own "serious, prestigious research mastery" |

**The winning gap:** a vertically-integrated, Arabic-first, *genuinely Saudi* research academy where one subscription unlocks a full novice→publication ladder with real assessments and Saudi-recognized certificates — a package none of the incumbents deliver.

---

## 2. Positioning & differentiators

**Positioning:** *Restrack is where a Saudi student or physician goes to move from "I want to do research" to a published, credentialed researcher* — premium, Arabic-first, examined, and unmistakably Saudi.

**The 12 differentiators we build around:**
1. **Unmistakably Saudi, not just Arabic** — SCFHS CME hours, SAR pricing, mada/Apple Pay/STC Pay/Tabby-Tamara, ZATCA invoices, NCBE/KACST IRB content, Saudi case studies.
2. **One flat subscription** unlocks the entire ladder (vs. per-course friction) — simpler, premium, higher LTV.
3. **Examined competency ladder** — prerequisite-gated levels, per-level graded exams (70% pass, **unlimited attempts**, randomized banks), portfolio capstone (a submission-ready manuscript).
4. **Saudi-recognized, verifiable bilingual certificates** with public QR verification — a credential global English platforms can't give Arabic learners.
5. **Learn-by-doing labs** (sample-size calc, run a t-test, appraise a paper, read a forest plot) with instant feedback — a clear step above lecture-only rivals.
6. **Genuinely luxurious** dark navy `#16264b` + gold `#af9136` RTL experience — Masterclass-grade — in a category that still looks like a government portal.
7. **Self-hosted encrypted video** (no YouTube/Vimeo) with per-student moving Arabic-name watermark + citable Saudi Copyright Law/SAIP deterrent.
8. **Fast/light & secure by design** — nginx-offloaded encrypted HLS behind a KSA-edge CDN, OWASP ASVS L2 / NCA ECC alignment, in-Kingdom residency.
9. **WhatsApp-first lifecycle** — certificates become a viral share/referral loop (~86% WhatsApp penetration competitors ignore).
10. **B2B institutional play** — seat licenses to med schools, deanships, hospital training depts.
11. **Ethics/IRB & publication integrity** (predatory journals, authorship, AI-use, PDPL) as a first-class module — a documented weak spot for Arab researchers.
12. **Vetted multi-instructor Saudi/GCC faculty** — removes the key-person risk Negida and MedONE both carry.

---

## 3. Personas

- **الطالب الجامعي (undergrad med/health/science):** mobile-first, time-poor, wants a clear path + a credential + a real research output. Fears paying and failing → **unlimited exam attempts** + free preview.
- **طبيب الامتياز/المقيم (intern/resident):** needs CME hours, publication skills, evening/mobile learning.
- **الباحث المبتدئ (junior researcher/grad):** wants advanced modules (systematic review, meta-analysis, publication) and templates/datasets.
- **المدرب (instructor):** a vetted faculty member who authors and uploads their own course/level content — never touches FFmpeg/DRM.
- **الأدمن (admin):** operates the whole platform — can change *anything*: content, users, roles, pricing, plans, coupons, refunds, theming, pages, reports.
- **المؤسسة (B2B buyer):** med school / hospital buying seat licenses with cohort reporting.

---

## 4. The product — full feature set

Tagged **[M]** must / **[S]** should / **[C]** could. This is the buildable backlog; sequencing is in `ROADMAP.md`.

### 4.1 Student experience
- **[M]** One all-access subscription (monthly + annual) unlocking the entire catalog & ladder.
- **[M]** Free preview/audit (intro lecture + first section) before the paywall.
- **[M]** Structured beginner→intermediate→advanced→expert tracks with prerequisite gating + a capstone per track.
- **[M]** Short 5–10 min recorded lectures; adaptive-bitrate HLS; resume, speed control, Arabic captions/transcripts.
- **[M]** Per-level graded exams: randomized banks, timed, explicit pass threshold (70%), **unlimited attempts**, auto-grading that gates progression.
- **[M]** Auto-issued verifiable bilingual (AR/EN) certificates + public QR verification + one-tap LinkedIn/WhatsApp share.
- **[M]** Interactive practice labs with instant feedback (sample size, t-test, appraise an abstract, forest plot, spot ethics violations).
- **[M]** Dashboard "mission control": continue-watching, level progress ring, streak, next exam, certificate shelf.
- **[M]** Mobile-first responsive **PWA** with low-data/data-saver video mode.
- **[S]** Question bank + spaced-repetition flashcards for retention.
- **[S]** Notes & bookmarks tied to video timestamps; per-lecture Q&A/discussion; dark mode.
- **[S]** Adaptive placement/skill assessment routing learners to the right level.
- **[S]** Adult gamification: streaks (Hijri/Gregorian-aware), XP, metallic badges, mastery rings, opt-in privacy-safe leaderboards.
- **[S]** Downloadable resources: slides, real datasets, STROBE/PRISMA/CONSORT templates, searchable knowledge base.
- **[C]** AI Arabic study tutor grounded *strictly* in Restrack's own lecture content (no PII to external AI, per PDPL).
- **[C]** Community/cohort layer: study groups, instructor office-hours, mentorship as a premium tier.

### 4.2 Instructor console
- **[M]** Course/level builder; video upload with **auto-transcode to encrypted HLS** (queued; instructor never touches FFmpeg/DRM).
- **[M]** Quiz/exam authoring (bilingual question banks); drip/prerequisite scheduling; lecture reorder (drag).
- **[M]** Scoped to own content only (every query ownership-checked — no cross-instructor leakage).
- **[S]** Instructor analytics: enrollment, completion, exam pass rates, watch-time.
- **[S]** Revenue/role visibility (if revenue-share model chosen).

### 4.3 Admin control center ("change anything")
- **[M]** User/role management (student/instructor/admin/super-admin) with **least-privilege RBAC**.
- **[M]** Course lifecycle **approval workflow** — instructor content is reviewed before publish (quality + NELC alignment).
- **[M]** Pricing/plans/coupons/trials; refunds; revenue & subscription reporting.
- **[M]** Full site CMS: edit **every** page section, hero copy, level names, FAQ, guidelines, speakers, nav, SEO, theme tokens — admin can change anything visible.
- **[M]** Content reorder/rename for how lessons appear to students.
- **[S]** Tamper-evident append-only audit log (logins, admin actions, payments, grade/exam edits, data exports).
- **[S]** Site-wide funnels/drop-off reports.
- **[S]** B2B/institutional seat-license portal (sub-accounts, cohort reporting, bulk enrollment).

### 4.4 Platform / cross-cutting
- **[M]** Local payments (mada + Apple Pay + STC Pay + Visa/MC 3-DS), SAR, tokenized auto-renewal.
- **[M]** ZATCA-compliant Arabic simplified tax invoice on every sale/renewal (15% VAT, TLV QR).
- **[M]** Secure self-hosted video pipeline (see §5).
- **[M]** Server-side authorization on **every** request (Policies/Gates, deny-by-default) guarding video/exams/certificates/subscription.
- **[M]** MFA (TOTP) mandatory for admin+instructor, optional for students; hardened sessions; secrets hygiene.
- **[M]** PDPL tooling: granular per-purpose consent, self-service export/delete (30 days), Arabic privacy notice, 72h breach runbook.
- **[M]** Concurrency/device limits per subscription + session revocation + anomaly detection (the real anti-sharing lever).
- **[M]** Adaptive-bitrate ladder (1080/720/480/360) + KSA/GCC-edge CDN + nginx `secure_link`/X-Accel offload.
- **[S]** WhatsApp-first lifecycle via CST-licensed BSP (Unifonic/Taqnyat): welcome, activation, abandoned-checkout, progress/streak, renewal, win-back.
- **[S]** Consent/preferences ledger (per-channel, per-purpose opt-in; one-click Arabic opt-out).
- **[S]** BNPL (Tabby/Tamara) on the **annual** plan only.
- **[S]** Recurring-billing resilience: dunning, smart retry, pre-renewal notice, self-serve cancel/upgrade.
- **[S]** Referral system: unique codes, double-sided reward on first paid sub, WhatsApp share, fraud guardrails.
- **[S]** CI security automation (composer/npm audit, SCA, SAST) + WAF/CDN.
- **[C]** Native iOS/Android apps with offline encrypted download + FairPlay/Widevine-L1 capture blocking (the only true screen-capture block).
- **[C]** Nafath identity verification for verified enrollment/certificate integrity.
- **[C]** Web push for logged-in re-engagement.

---

## 5. Roles & permissions matrix

Guard: **spatie/laravel-permission**, single `web` guard, **deny-by-default Policies/Gates on every resource**. Current roles are `super_admin`, `admin`, `student`; we **add `instructor`**.

| Capability | student | instructor | admin | super_admin |
|---|:--:|:--:|:--:|:--:|
| Watch lectures / take exams / earn certificates (own) | ✅ | ✅ | ✅ | ✅ |
| Manage own profile / subscription | ✅ | ✅ | ✅ | ✅ |
| Create/edit **own** courses, levels, lectures, exams | — | ✅ | ✅ | ✅ |
| Upload video (own content only) | — | ✅ | ✅ | ✅ |
| View analytics for **own** content | — | ✅ | ✅ | ✅ |
| Publish content (requires approval) | — | request | ✅ approve | ✅ approve |
| Edit **any** course / reorder / rename | — | — | ✅ | ✅ |
| Manage users & assign roles | — | — | ✅ | ✅ |
| Pricing, plans, coupons, refunds | — | — | ✅ | ✅ |
| Full site CMS (pages, hero, theme, SEO, nav) | — | — | ✅ | ✅ |
| View audit logs / security settings | — | — | ✅ | ✅ |
| Manage other admins / destructive/global config | — | — | — | ✅ |

**Rule:** every instructor and student query is ownership-scoped; privilege escalation paths (role assignment, publish, refunds) are `super_admin`/`admin` only and audit-logged.

**Access model:** **one active subscription unlocks ALL courses/levels** platform-wide (no per-course entitlement). Enforced centrally in the subscription gate (status active **AND** not expired).

---

## 6. Curriculum architecture

**Research Track Programs (1) — From Beginner to Expert in Medical Research** (the launch track; the platform supports multiple tracks/programs).

| Level | Name | Focus | Canonical topics |
|---|---|---|---|
| **1** | Beginner Researcher | Foundations of Medical Research | Intro to research, ethics & IRB, literature search, research questions, study types, referencing |
| **2** | Intermediate Researcher | Research Design & Data Management | Study design, sampling, data collection, statistical basics, proposal writing, data mgmt, bias/confounding |
| **3** | Expert Researcher | Scientific Writing & Publication | Manuscript writing, journal selection, peer review, systematic reviews, publication ethics, research impact, grants |

**Reference spine to match/exceed** (Negida C1–C7 + IRB): research methodology → biostatistics (SPSS **and** R) → sample size → scientific writing → systematic review/meta-analysis → GRADE/critical appraisal → IRB/ethics → publication. Each level: recorded lectures → practice labs → **graded exam (70%, unlimited attempts)** → certificate. Full track → capstone (a submission-ready manuscript) + final certificate.

---

## 7. Video protection & delivery (self-hosted, layered, honest)

**Honest bar first:** no in-browser tech fully stops screenshots/screen-recording. The goal: block casual download/sharing, make recording **traceable**, keep true capture-blocking for native apps. (Full detail in `CLAUDE.md` §7.)

| # | Layer | Purpose |
|---|---|---|
| 1 | **Access control** (highest ROI) — subscription-gate every manifest, segment, **and key** behind short-TTL (60–300s) signed URLs; block hotlinking/referrer | a leaked link dies before it can be shared |
| 2 | **Encrypted HLS** — FFmpeg AES-128 with **rotating keys**; auto-transcode on upload via queued workers; raw masters off the origin | one leaked key ≠ whole video |
| 3 | **Authenticated key delivery** — AES key URI routed through a Laravel route that checks auth + entitlement | key is never a static file |
| 4 | **Web-server offload** — serve `.ts` via nginx `secure_link` / X-Accel-Redirect (Apache: `mod_xsendfile`) | PHP out of the hot path → fast |
| 5 | **Per-student moving watermark** (#1 deterrent) — subtle overlay: student's real Arabic name (correct shaping/bidi) + masked account id, shifting position/opacity | survives into any recording; ties a leak to an account (masked id per PDPL) |
| 6 | **Concurrency/device limits + anomaly detection** — cap simultaneous streams/devices; revoke sessions; flag impossible-travel | catches the real leak vector (credential sharing) |
| 7 | **KSA/GCC-edge CDN** + SD fallback rendition | fast start, never black-screens |
| 8 | **Localized legal deterrent** — Arabic one-time terms + in-player copyright notice citing 2026 Saudi Copyright Law/SAIP penalties | credible jurisdiction-specific warning |
| 9 | **Native apps** (only if capture-blocking becomes hard requirement) — FairPlay / Widevine-L1 | the only real screen-capture block; sell as premium "certified secure viewing" |
| 10 | **Multi-DRM** (only if leak analytics prove organized ripping) — EZDRM/Axinom or Bunny MediaCage/VdoCipher | conscious budget choice, not launch default |

**Do NOT** rely on right-click traps / dev-tools theatrics (trivially bypassed, hurt the premium feel). Instrument leak analytics from day one so any DRM escalation is data-driven.

---

## 8. Cybersecurity & KSA compliance

Build to **OWASP ASVS 5.0 Level 2** and check **OWASP Top 10:2025** per feature (priorities: A01 access control, A02 misconfig, A03 supply chain). Adopt **NCA ECC** as the voluntary national baseline (MFA, encryption in transit+at rest w/ key mgmt, tested backups/DR, monitoring, vuln scanning + annual pentest, third-party controls).

**PDPL (SDAIA) — enforceable since 14 Sep 2024:**
- Document a DPO/responsible person, a Record of Processing (RoPA), and a DPIA.
- Granular, per-purpose, withdrawable consent (timestamp/source); fulfill access/export/correction/delete within **30 days** via an Arabic self-service center.
- Treat **health-interest + national-ID** data as **sensitive** — explicit consent only; extra safeguards (unlawful disclosure: up to SAR 3m + prison; general fines up to SAR 5m).
- Breach: notify SDAIA within **72h** via the National Data Governance Platform; notify high-risk users.
- Cross-border: assess every non-KSA processor/CDN/analytics vendor; **host personal data + video in a Saudi/GCC region** (STC/Google/Oracle/AWS KSA or AWS Bahrain) — the low-risk, premium, low-latency choice.

**Account & app security:** MFA for admin+instructor; hardened sessions; rate-limiting; CSP + security headers; secrets rotated & distinct per service; dependency scanning; WAF/CDN; tamper-evident audit log.

---

## 9. Payments, tax & billing

- **Gateway:** a SAMA-licensed, PCI DSS L1 gateway — **Moyasar** (fastest Laravel launch, transparent mada pricing) or **HyperPay** (turnkey tokenized recurring + dunning + built-in ZATCA Phase-2). Keep PCI scope at **SAQ-A** via hosted/tokenized checkout — raw card data never touches the app.
- **Methods:** mada, Apple Pay, STC Pay, Visa/MC (3-D Secure); **BNPL (Tabby/Tamara) on annual only**.
- **Subscription lifecycle:** tokenized card-on-file auto-renewal; dunning + smart retry; pre-renewal notice; self-serve cancel/upgrade in Arabic.
- **Tax:** standard **15% VAT** on digital course subscriptions (do **not** assume education relief); VAT-inclusive SAR pricing with itemized tax; register once turnover > SAR 375,000/yr.
- **ZATCA FATOORA:** compliant Arabic simplified tax invoice per sale/renewal (VAT number, ISO timestamp, total incl. VAT, VAT amount, **TLV QR**); Phase-2 = signed UBL 2.1 XML + UUID + cryptographic stamp reported within 24h — confirm applicable wave.
- **Access rule:** one active subscription → all content (§5).

---

## 10. Certificates & credentials

- Auto-issued on level pass and on full-track completion. Title: **Certificate of Completion / شهادة إكمال** (drop "accredited" until accreditation is real).
- Bilingual (AR/EN), brand-styled, with student name (correct Arabic shaping), program/level, unique number (collision-safe), **public QR verification** page, issue date.
- **Emailed** (queued) on completion; shareable vertical card for WhatsApp/LinkedIn (feeds the referral loop).
- **Accreditation path (owner decision):** launch on **NELC-licensed** certificates; pursue **SCFHS CME hours** where feasible (scientific committee + accredited CPD provider). Make no "accredited" claim until confirmed with Saudi counsel.

---

## 11. Notifications & marketing (WhatsApp-first, consent-safe)

- **Channels:** transactional + promotional **email**; **SMS** (Unifonic/Taqnyat); **WhatsApp Business API** via a CST-licensed BSP (~86% KSA penetration); lightweight **web push** for logged-in users.
- **Lifecycle flows:** welcome/onboarding, activation, **abandoned-checkout recovery** (highest ROI), progress/streak nudges, certificate-earned share, renewal reminders, win-back.
- **Compliance (CST + PDPL):** pre-registered alphanumeric sender; honor DND registry + send-hours (~08:00–22:00, Ramadan-shifted); **separate per-channel per-purpose opt-in** with one-click Arabic opt-out; transactional (OTP/receipt/certificate) messages carry **no** promotion.
- **Engine:** Laravel Notifications + queues; consent ledger; referral system fired at the certificate-earned moment.

---

## 12. Performance & architecture ("خفيفة وسريعة")

- **Video never through PHP** — nginx/Apache offload + KSA-edge CDN + ABR ladder; PHP only mints tokens/checks entitlement.
- **Cache hot paths** (homepage, catalog, settings) with `Cache::remember` (per-locale); currently zero cache usage.
- **Self-host fonts** (subset Arabic woff2), **bundle Alpine/hls.js** (no CDN), tame always-on animations behind `prefers-reduced-motion`, lazy images + explicit dimensions.
- **Mobile-first PWA**, data-saver mode, sub-second perceived load; ship `npm run build` + `config:cache route:cache view:cache`.
- **Queues** (`database` driver) for transcoding, emails, invoices, notifications — never inline in a web request.
- Kill N+1s (eager-load); paginate; index hot columns.

---

## 13. Automatic SEO engine (bilingual, best-in-class)

**Goal:** a fully **automatic**, bilingual (AR/EN) SEO layer that *exceeds* a Yoast/RankMath-configured WordPress site — because it's built into the app (not a bolted-on plugin), is faster (Core Web Vitals are a ranking factor), and is **natively bilingual** (a mono-language WP site cannot match this).

**Honest framing (constitution §2.7):** no tool — not Yoast, not RankMath, not this — can *guarantee* the #1 spot or Google **sitelinks** within two weeks. Ranking also needs content depth, backlinks/authority, and time; Google generates sitelinks itself and cannot be forced. What we **can** do is feed Google *every* technical signal it wants, automatically and more completely than any plugin — which maximizes fast indexing, rich results, and **sitelink eligibility**. We build the automatic technical 90%; content + backlinks are the owner's ongoing 10%.

**Automatic technical foundation (every page, zero manual work):**
- Semantic HTML5, exactly one `<h1>`, logical heading order, descriptive `alt` enforced on images.
- **Core Web Vitals green** (LCP/INP/CLS) via the fast/light architecture (§12) — a direct ranking signal.
- Canonical URLs, clean localized slugs, HTTPS, mobile-first, breadcrumb nav.
- **Bilingual `hreflang`** (`ar`, `en`, `x-default`) on every page + localized `<title>`/`<meta>` per locale — the single biggest edge over a mono-language WordPress site.
- **Auto XML sitemaps** (pages, courses, lectures/video, images) + sitemap index, **regenerated on every content change**; `robots.txt`; auto-ping Google/Bing; **IndexNow** (+ optional Google Indexing API) for near-instant indexing of new content — the answer to "the site is new, make it show up fast."

**Structured data (JSON-LD schema.org) — auto-injected per content type** (this is what earns rich results + sitelink eligibility):
- `Organization` / `EducationalOrganization` (logo, contact, `sameAs` socials) + `WebSite` with **`SearchAction`** (sitelinks search-box eligibility).
- `Course` + `CourseInstance` — the killer schema for an education site (course rich results).
- `VideoObject` for lectures (video rich results — respecting the protected self-hosted model; expose only public preview metadata).
- `BreadcrumbList` (breadcrumbs in SERP), `FAQPage` (FAQ rich results), `Person` (instructors), `AggregateRating`/`Review` once ratings exist.

**Meta & social — automatic, admin-overridable:**
- Title/description templates per page type with length-optimized smart fallbacks + brand suffix; admin can override any field.
- Open Graph + Twitter Cards auto; **dynamic OG images** per course/certificate (branded, Arabic-correct).

**Admin SEO cockpit (RankMath-grade, native + bilingual):** per-page editable meta with live Google/social preview, focus-keyword hints & score, **301 redirect manager**, `noindex`/`nofollow` control, sitemap status, and a Core-Web-Vitals/health dashboard — all editable by the admin (per "admin can change anything").

**Analytics/consent:** Google Search Console + a privacy-respecting analytics choice, loaded consent-gated (PDPL) and non-blocking so it never hurts Core Web Vitals.

**Build on existing code:** the repo already has `SeoMeta` + `SeoService` + `SeoController` — we elevate them into this engine rather than starting from scratch.

---

## 14. Design system — premium Arabic RTL (the "wow")

**Principle:** Arabic-first, RTL-native from the first wireframe — never a mirrored English theme. Nav/logo anchor right; sidebars right; progress fills right-to-left; flip *directional* icons only (arrows/chevrons/back/send), not search/play/clock/check.

**Both a night mode and a day mode (مود ليلي + مود نهاري)** — dark is the **signature default** (premium), light is a fully-polished equal, not an afterthought. Toggle persists per user (localStorage + user pref column) and honors `prefers-color-scheme`; both must pass WCAG contrast. Drive both from CSS custom properties in the `@theme` block so a single token swap flips the theme (no duplicated components).

- **Dark (default, luxury):** Canvas `#0D1830` · Surface-1 `#16264b` (brand) · Surface-2/hover `#1D3160` · gold hairline borders, soft gradients, layered elevation. Gold text/links use antique gold **`#D4B45A`** (7.4:1 on navy); body = warm off-white **`#F4F1EA`** (13:1); muted `#A9B2C3`.
- **Light (day, clean-premium):** Canvas `#F7F5EF` (warm paper, not stark white) · Surface `#FFFFFF` · text navy `#16264b`/`#0D1830` · hairline `#E7E1D3`. Gold **as text/links must darken** to `#8A7028` (gold-dark, ≈4.7:1 on white) — `#af9136` is only 3.0:1 on white, so never use it for body/links on light; keep bright gold only for filled CTAs/badges with dark text.
- **Gold `#af9136` used like real gold in BOTH themes** — one primary CTA per view, active nav, progress fill, badge/certificate foil, hairline dividers — with a metallic sheen gradient (`#af9136 → #d4b45a`). Reserve it; scarcity is what reads as premium.

**Chosen aesthetic direction: "Vibrant Premium" (①) — youthful/cheerful, not stiff.** The owner selected an energetic-yet-credible look for Saudi university students. On the navy+gold foundation, add a **vibrant accent system** used generously for delight (cards, tags, gamification, illustrations) while gold stays reserved for prime CTAs/prestige:
- **Violet `#7C6CFC`** (primary vibrant accent / links / focus) · **Teal `#10B4A0`** (progress/success energy) · **Bright gold `#FFB400`** (playful gold for badges/XP) · **Coral `#FF7A59`** (streaks/highlights).
- **Semantic:** success `#12B39B`, warning `#F5A524`, danger `#F0506E` — distinct from accents.
- **Feel:** rounded friendly shapes (larger radii), soft colorful shadows, tasteful emoji/illustration, blob accents, bouncy micro-interactions, **front-and-center gamification** (XP pills, streak flames, level badges, mastery rings). Energetic — never "children's app": keep type crisp, spacing generous, gold scarce.
- Live reference: the approved "Vibrant Premium" board (Direction ①).

**Type:** self-hosted subsetted **IBM Plex Sans Arabic** (harmonized dual-script — ideal for bilingual medical terms) or Tajawal; body 16–18px, line-height 1.7–1.85, letter-spacing 0. **Western-Arabic numerals (1,2,3)** consistently — never mix numeral systems on one screen.

**Layout tech:** logical CSS everywhere (`dir=rtl`+`lang=ar` at root, `margin-inline`/`padding-inline`, `text-align:start`, bidi-isolation) so English drug names/DOIs/% render cleanly inside Arabic.

**Signature moments:** cinematic full-bleed dark hero with muted looping preview + gold Arabic display headline + **one** gold CTA; distraction-free RTL player (Arabic captions/transcripts, speed, resume, timestamped notes); restrained glassmorphism (nav, modals, sticky player, CTA cards only, always ≥4.5:1); a small motion system (durations/easing, gold shimmer on certificate unlock, level-node fill) respecting `prefers-reduced-motion`.

**Adult gamification:** streaks (Hijri/Gregorian-aware), XP, mastery rings, tiered metallic/engraved badges, opt-in privacy-safe leaderboards (nicknames, gender-considerate) — *earned prestige, not cartoon confetti*.

**Saudi lens (generic-premium → made-for-me):** Hijri-alongside-Gregorian dates, prayer-time-aware / Ramadan-shifted reminders, subtle low-opacity Islamic-geometry texture on dividers/badges/certificate borders (not clip-art), gender-considerate iconography over stock photos.

**Standards:** WCAG 2.2 AA — keyboard-operable, visible focus, correct lang/dir for screen readers, mobile-first thumb ergonomics (primary actions right), tested with **real Arabic strings** on iOS Safari + Android Chrome.

**Share assets:** vertical, share-native certificate & streak cards (TikTok/Snapchat polish) with the student's Arabic name typeset correctly — feeding the WhatsApp/LinkedIn loop.

---

## 15. Phased delivery roadmap (strategy → execution)

Tactical detail & status live in `ROADMAP.md`. Strategic phases:

- **Phase 0 — Foundation & safety:** env (PHP 8.2, `composer install`), rotate `.env` secrets, KSA/GCC hosting decision, baseline tests, add `instructor` role + Policies scaffolding.
- **Phase 1 — Content, brand & themes (quick wins):** hero copy (gold "Research Track Platform" + "From Beginner to Expert…"), "Restrack is…" text, level renames, remove "accredited", "recorded lectures" + "unlimited attempts" messaging, Read-more program detail, guideline logos, design-system tokens, **light + dark mode (مود نهاري/ليلي)**, bilingual AR/EN parity pass.
- **Phase 1.5 — Automatic SEO engine (§13):** hreflang, auto sitemaps + IndexNow, JSON-LD schema (Organization/WebSite+SearchAction/Course/Breadcrumb/FAQ), OG/Twitter + dynamic OG images, admin SEO cockpit — built early so every later page ships SEO-complete.
- **Phase 2 — Certificates:** missing PDF template (Certificate of Completion / شهادة إكمال) + email on completion + QR verify + collision-safe numbers.
- **Phase 3 — Exam hardening:** confirm randomized banks + unlimited attempts; fix session-collision/type-safety; surface in FAQ.
- **Phase 4 — Payments end-to-end:** Moyasar/HyperPay + pre-payment program-detail page + mada/Apple Pay/STC Pay + VAT + ZATCA invoice + signed webhook + expiry enforcement.
- **Phase 5 — Roles & portals:** instructor portal (author/upload own content) + admin approval workflow + reorder/rename + all-powerful CMS + MFA.
- **Phase 6 — Self-hosted video + protection:** the 10-layer stack (§7) — the flagship, biggest effort.
- **Phase 7 — Security & compliance hardening:** PDPL tooling, audit log, CSP/headers, concurrency/device limits, CI security automation.
- **Phase 8 — Growth:** WhatsApp lifecycle, referrals, gamification, B2B seat licenses, AI Arabic tutor.
- **Performance pass** runs throughout and as a final sweep.

---

## 16. Open decisions for the owner

1. **Accreditation:** pursue SCFHS CME now / later, or launch on NELC-licensed certificates first? (Gates the "certified" claim.)
2. **NELC licensing:** does a private paid non-degree platform need a NeLC license + FutureX to issue certificates? (Confirm with Saudi counsel — potentially launch-blocking; make no "accredited" claim until then.)
3. **Payment gateway:** Moyasar (fast launch, DIY subscriptions) vs. HyperPay (turnkey recurring + ZATCA Phase-2)?
4. **Pricing:** monthly vs. annual SAR price points (VAT-inclusive), student discount, trial length, optional live-cohort premium tier?
5. **Video-protection budget:** launch lean (AES-128 HLS + watermark + concurrency, ~90% of the threat) and escalate on leak data — recommended.
6. **Content supply:** single hero instructors vs. vetted faculty marketplace; revenue-share/role model; initial library size to justify the subscription.
7. **B2B timing:** institutional seat licenses at launch or fast-follow?
8. **Hosting:** which Saudi/GCC region/provider (STC/Google/Oracle/AWS KSA vs. AWS Bahrain)? — decide before building auth/storage/video origin.
9. **Data-sensitivity legal read:** does collected data legally count as PDPL "sensitive"? (Changes consent, watermark PII, penalties.)
10. **Native app timing:** offline encrypted download + true capture-blocking at launch or fast-follow?

---

*Master Plan v1 — grounded in live 2026 research. Revisit as owner decisions in §15 land.*
