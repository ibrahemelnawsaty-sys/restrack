@extends('layouts.app')

@section('title', 'Restrack — Research Track Platform · '.__('من المبتدئ إلى الخبير في البحث الطبي'))

@php
  // Every public string on this page comes from the owner's deck, editable at /admin/content.
  // docs/plan/CONTENT-PLAN.md §3 maps each key to its slide.
  $t = fn (string $section, string $key, string $default = '') => \App\Models\PageSection::text('home', $section, $key, $default);
  $groupOrder = \App\Models\Guideline::GROUPS;
  $allGuidelines = collect($groupOrder)->flatMap(fn ($g) => $guidelines[$g] ?? [])->values();
@endphp

@section('content')
  {{-- ===== 1. hero — short name + full name + the tagline + a plain definition (owner note م1) ===== --}}
  <section class="hero">
    <div class="hero-grid">
      <div class="rise in">
        <span class="kick glass"><span class="dot"></span>{{ $t('hero', 'kicker', __('مؤسسة ريستراك للتدريب')) }}</span>
        <h1><span class="grad-gold">{{ $t('hero', 'highlight', 'Research Track Platform') }}</span></h1>
        <p class="sub">{{ $t('hero', 'subtitle', 'From Beginner to Expert in Medical Research') }}</p>
        <p class="lead">{{ $t('hero', 'lead') }}</p>
        <div class="cta-row">
          <a class="btn btn-gold" href="{{ auth()->check() ? route('dashboard') : route('register') }}"><span class="sheen"></span>{{ $t('hero', 'cta_primary', __('ابدأ رحلتك')) }}<svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="btn btn-ghost" href="#program">{{ $t('hero', 'cta_secondary', __('تعرّف على البرنامج')) }}</a>
        </div>
        <div class="trust">
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg>{{ $t('hero', 'pill_levels', __('3 مستويات متدرّجة')) }}</span>
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ $t('hero', 'pill_attempts', __('محاولات اختبار لا محدودة')) }}</span>
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg>{{ $t('hero', 'pill_cert', __('شهادة لكل مستوى')) }}</span>
        </div>
      </div>

      <div class="glass sheen dashcard rise in" id="dash" aria-label="{{ __('لوحة الطالب — محاكاة') }}">
        <div class="dc-head"><b>{{ __('لوحتي') }}</b><span class="t"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg>{{ __('اليوم') }}</span></div>
        <div class="ringwrap">
          <svg class="ring" id="ring" viewBox="0 0 100 100" aria-hidden="true"><circle class="tk" cx="50" cy="50" r="45"/><circle class="pr" cx="50" cy="50" r="45"/></svg>
          <div class="rl"><b class="num" data-count="70" data-suffix="%">0%</b><span>{{ __('تقدّم المستوى الأول') }}</span></div>
        </div>
        <div class="dc-stats">
          <div class="s g"><svg class="ico" aria-hidden="true"><use href="#i-flame"/></svg><div><b class="num">12</b><span>{{ __('يوم متتالٍ') }}</span></div></div>
          <div class="s v"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg><div><b class="num">86%</b><span>{{ __('متوسط الاختبارات') }}</span></div></div>
          <div class="s t"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg><div><b class="num">24</b><span>{{ __('محاضرة مكتملة') }}</span></div></div>
          <div class="s c"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg><div><b style="font-size:.9rem">{{ __('شهادتي') }}</b><span>{{ __('عند الإكمال') }}</span></div></div>
        </div>
      </div>
    </div>

    {{-- owner note م2: one long page — the visitor scrolls down and the content unfolds --}}
    <a class="scroll-cue rise" href="#standards">
      <span>{{ $t('hero', 'scroll_cue', __('انزل لتتعرّف على البرنامج')) }}</span>
      <svg class="ico" aria-hidden="true"><use href="#i-chevron"/></svg>
    </a>
  </section>

  {{-- ===== 2. standards marquee — credibility first (owner note م4) ===== --}}
  @if ($allGuidelines->isNotEmpty())
    <section id="standards" class="strip-wrap" aria-label="{{ __('المعايير التي نلتزم بها') }}">
      <div class="strip glass rise">
        <div class="strip-track">
          @foreach ($allGuidelines->concat($allGuidelines) as $g)
            <span class="glogo" @if ($g->note_ar) title="{{ $g->note_ar }}" @endif aria-hidden="{{ $loop->index >= $allGuidelines->count() ? 'true' : 'false' }}">
              @if ($g->logo)
                <img src="{{ asset('storage/'.$g->logo) }}" alt="{{ $g->name_en ?: $g->name_ar }}" width="112" height="40" loading="lazy" decoding="async">
              @else
                <bdi>{{ $g->name_en ?: $g->name_ar }}</bdi>
              @endif
            </span>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- ===== 3. who we are + vision & mission (deck slides 2 & 5) ===== --}}
  <section id="about">
    <div class="split about-split">
      <div class="glass tile rise">
        <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-sparkle"/></svg>{{ $t('about', 'title', __('من نحن')) }}</span>
        <p class="lead" style="margin-top:14px">{{ $t('about', 'text') }}</p>
      </div>
      <div class="vm rise">
        <div class="glass tile">
          <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-sparkle"/></svg></div>
          <h3>{{ $t('vision', 'vision_title', __('رؤيتنا')) }}</h3>
          <p>{{ $t('vision', 'vision') }}</p>
        </div>
        <div class="glass tile">
          <div class="chip teal"><svg class="ico" aria-hidden="true"><use href="#i-research"/></svg></div>
          <h3>{{ $t('vision', 'mission_title', __('رسالتنا')) }}</h3>
          <p>{{ $t('vision', 'mission') }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ===== 4. goals + values (deck slides 3 & 4) ===== --}}
  <section id="goals" style="padding-top:0">
    <div class="shead rise">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg>{{ $t('goals', 'title', __('أهدافنا')) }}</span>
      <h2>{{ __('ما الذي نعمل من أجله') }}</h2>
    </div>
    <div class="fgrid stagger rise" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
      @foreach (['g1' => 'i-users', 'g2' => 'i-chart', 'g3' => 'i-globe', 'g4' => 'i-layers'] as $key => $icon)
        <div class="glass sheen fcard goal">
          <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#{{ $icon }}"/></svg></div>
          <p>{{ $t('goals', $key) }}</p>
        </div>
      @endforeach
    </div>

    <div class="values rise">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>{{ $t('values', 'title', __('قيمنا')) }}</span>
      <div class="vrow">
        @foreach (['v1', 'v2', 'v3', 'v4'] as $key)
          <span class="vchip glass"><svg class="ico" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $t('values', $key) }}</span>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ===== 5. target audience (deck slide 7) ===== --}}
  <section id="audience" style="padding-top:0">
    <div class="glass tile rise aud-tile">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-users"/></svg>{{ $t('audience', 'title', __('لمن هذه المنصة؟')) }}</span>
      <p style="color:var(--ink-2);margin-top:10px;max-width:70ch">{{ $t('audience', 'intro') }}</p>
      <div class="aud">
        @foreach (['a1', 'a2', 'a3', 'a4', 'a5'] as $key)
          <span class="achip"><svg class="ico" aria-hidden="true"><use href="#i-cap"/></svg>{{ $t('audience', $key) }}</span>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ===== 6. why choose us (deck slide 6) ===== --}}
  <section id="features" style="padding-top:0">
    <div class="shead rise">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-sparkle"/></svg>{{ $t('why', 'title', __('لماذا تختارنا؟')) }}</span>
      <h2>{{ __('أربعة أسباب تجعل المسار مختلفاً') }}</h2>
    </div>
    <div class="fgrid stagger rise" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr))">
      @foreach (['w1' => ['i-layers', 'gold'], 'w2' => ['i-users', 'violet'], 'w3' => ['i-globe', 'teal'], 'w4' => ['i-shield', 'coral']] as $key => [$icon, $tone])
        <div class="glass sheen fcard">
          <div class="chip {{ $tone }}"><svg class="ico" aria-hidden="true"><use href="#{{ $icon }}"/></svg></div>
          <h3>{{ $t('why', $key.'_t') }}</h3>
          <p>{{ $t('why', $key.'_b') }}</p>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ===== 7. the program — the commercial heart (owner notes م5 · م6) ===== --}}
  <section id="program">
    <div class="glass prog rise">
      <div class="prog-head">
        <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-cap"/></svg>{{ __('البرنامج') }}</span>
        <h2><bdi class="grad-gold">{{ $t('program', 'name', 'Research Track 1') }}</bdi></h2>
        <p class="sub"><bdi>{{ $t('program', 'tagline', 'From Beginner to Expert in Medical Research') }}</bdi></p>
        <p class="lead">{{ $t('program', 'about') }}</p>
      </div>

      <div class="prog-includes">
        @foreach (['i1' => 'i-layers', 'i2' => 'i-infinity', 'i3' => 'i-award', 'i4' => 'i-award', 'i5' => 'i-video'] as $key => $icon)
          <div class="inc">
            <span class="li"><svg class="ico" aria-hidden="true"><use href="#{{ $icon }}"/></svg></span>
            <span>{{ $t('program', $key) }}</span>
          </div>
        @endforeach
      </div>

      <p class="prog-closing">{{ $t('program', 'closing') }}</p>

      <div class="cta-row" style="justify-content:center">
        <a class="btn btn-gold" href="{{ auth()->check() ? route('dashboard') : route('register') }}"><span class="sheen"></span>{{ __('ابدأ الآن') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="btn btn-ghost" href="#pricing">{{ __('السعر والاشتراك') }}</a>
      </div>
    </div>
  </section>

  {{-- ===== 8. the three levels (from the database) ===== --}}
  <section style="padding-top:0">
    <div class="shead center rise">
      <span class="eyebrow" style="justify-content:center"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg>{{ __('إطار التعلّم') }}</span>
      <h2>{{ __('ثلاثة مستويات — كل مستوى يبني على ما قبله') }}</h2>
      <p>{{ __('كل مستوى مجموعة محاضرات مسجّلة، ينتهي باختبار، ويمنحك شهادة إتمام تحمل درجتك.') }}</p>
    </div>

    <div class="ladder stagger rise">
      @foreach ($levels as $level)
        <div class="glass sheen lvl">
          <div class="node num">{{ $level->sort_order }}</div>
          <div class="en"><bdi>{{ $level->name_en }}</bdi></div>
          <div class="ar">{{ $level->name_ar }}</div>
          <div class="focus">{{ $level->focus_ar }}</div>
          <div class="topics">
            @foreach (array_slice($level->topics_ar ?? [], 0, 5) as $topic)
              <span>{{ $topic }}</span>
            @endforeach
          </div>
          {{-- owner note م8: the learner sees "0/6" up front, so the size of each level is obvious --}}
          <div class="lvl-count"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg><span class="num">0/{{ $level->lectures->count() }}</span> {{ __('محاضرة') }}</div>
          <div class="exam"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ __('ينتهي باختبار · محاولات لا محدودة') }}</div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ===== 9. the standards in full (deck slides 9–11) ===== --}}
  @if ($allGuidelines->isNotEmpty())
    <section id="guidelines" style="padding-top:0">
      <div class="shead rise">
        <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>{{ $t('guidelines', 'title', __('المعايير التي نلتزم بها')) }}</span>
        <h2>{{ __('محتوىً مبنيّ على مرجعية، لا على اجتهاد') }}</h2>
        <p>{{ $t('guidelines', 'intro') }}</p>
      </div>
      <div class="gl-groups stagger rise">
        @foreach ($groupOrder as $group)
          @continue (empty($guidelines[$group]) || count($guidelines[$group]) === 0)
          <div class="glass tile gl-group">
            <h3>{{ \App\Models\Guideline::groupLabel($group) }}</h3>
            <div class="gl-items">
              @foreach ($guidelines[$group] as $g)
                <span class="glogo sm" @if ($g->note_ar) title="{{ $g->note_ar }}" @endif>
                  @if ($g->logo)
                    <img src="{{ asset('storage/'.$g->logo) }}" alt="{{ $g->name_en ?: $g->name_ar }}" width="96" height="34" loading="lazy" decoding="async">
                  @else
                    <bdi>{{ $g->name_en ?: $g->name_ar }}</bdi>
                  @endif
                </span>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif

  {{-- ===== 10. our speakers (deck slide 13 · owner note م3) ===== --}}
  <section id="speakers" style="padding-top:0">
    <div class="shead rise">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-users"/></svg>{{ $t('speakers', 'title', __('متحدثونا')) }}</span>
      <h2>{{ __('من سيعلّمك؟') }}</h2>
      <p>{{ $t('speakers', 'intro') }}</p>
    </div>

    @if ($speakers->isNotEmpty())
      <div class="spk stagger rise">
        @foreach ($speakers as $s)
          <div class="glass sheen spk-card">
            <div class="ava">
              @if ($s->avatar)
                <img src="{{ asset('storage/'.$s->avatar) }}" alt="{{ $s->name_ar }}" width="72" height="72" loading="lazy" decoding="async">
              @else
                <span aria-hidden="true">{{ $s->initials() }}</span>
              @endif
            </div>
            <b>{{ $s->name_ar }}</b>
            <span class="cred">{{ $s->credential_ar ?: $s->title_ar }}</span>
            @if ($s->highlight_ar)<span class="hl"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg>{{ $s->highlight_ar }}</span>@endif
          </div>
        @endforeach
      </div>
    @endif

    <div class="crit rise">
      @foreach (['c1', 'c2', 'c3'] as $key)
        <span class="vchip glass"><svg class="ico" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $t('speakers', $key) }}</span>
      @endforeach
    </div>
  </section>

  {{-- ===== 11 + 12. delivery model & quality assurance (deck slides 12 & 14) ===== --}}
  <section style="padding-top:0">
    <div class="split">
      <div class="glass tile rise">
        <div class="chip violet"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg></div>
        <h3 style="margin-top:12px">{{ $t('delivery', 'title', __('نموذج التعلّم')) }}</h3>
        <p style="color:var(--ink-2);margin-top:8px">{{ $t('delivery', 'body') }}</p>
      </div>
      <div class="glass tile rise">
        <div class="chip teal"><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg></div>
        <h3 style="margin-top:12px">{{ $t('quality', 'title', __('ضمان الجودة')) }}</h3>
        <p style="color:var(--ink-2);margin-top:8px">{{ $t('quality', 'body') }}</p>
        <div class="aud" style="margin-top:14px">
          @foreach (['q1', 'q2', 'q3', 'q4', 'q5'] as $key)
            <span class="achip sm">{{ $t('quality', $key) }}</span>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- ===== 13. certificates ===== --}}
  <section class="certwrap">
    <div class="glass cert rise">
      <div class="rose"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
      <h3>Certificate of Completion</h3>
      <div class="ar">{{ __('شهادة إكمال') }}</div>
      <div class="who">{{ __('اسم المتعلّم') }}</div>
      <div class="desc">{{ __('شهادة إتمام لكل مستوى تحمل الدرجة التي اجتزتَه بها، وشهادة نهائية عند إكمال المسار — لكلٍّ منها رقم فريد وصفحة تحقّق عامة.') }}</div>
      <div class="foot"><span class="num">RST-2026-XXXXXXXX</span><span class="qr"><svg class="ico" aria-hidden="true"><use href="#i-globe"/></svg>{{ __('تحقّق عبر QR') }}</span><span class="num">{{ date('Y') }}</span></div>
    </div>
  </section>

  {{-- ===== 14. pricing — one annual subscription (owner notes م7 · م9) ===== --}}
  <section id="pricing">
    <div class="shead center rise">
      <span class="eyebrow" style="justify-content:center"><svg class="ico" aria-hidden="true"><use href="#i-wallet"/></svg>{{ $t('pricing', 'title', __('اشتراك سنوي واحد · المسار كامل')) }}</span>
      <h2>{{ __('اشتراك واحد يفتح المسار كاملاً') }}</h2>
    </div>

    <div class="plans stagger rise" style="max-width:520px;margin-inline:auto">
      @foreach ($plans as $plan)
        <div class="glass sheen plan @if ($plan->is_featured) feat @endif">
          <div class="pt"><b><bdi>{{ $plan->name_ar }}</bdi></b></div>
          <div class="amt"><span class="num n">{{ (int) $plan->price }}</span><span class="cur">{{ __('ر.س') }}</span><span class="per">/ {{ $plan->interval === 'annual' ? __('سنوياً') : __('شهرياً') }}</span></div>
          <ul>
            @foreach (($plan->features_ar ?? []) as $feature)
              <li><svg class="ico" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $feature }}</li>
            @endforeach
          </ul>
          {{-- م9: the learner must know attempts are unlimited BEFORE paying — so it sits above the button --}}
          <p class="reassure"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg><span>{{ $t('pricing', 'note_unlimited') }}</span></p>
          <a class="btn {{ $plan->is_featured ? 'btn-gold' : 'btn-ghost' }} full" href="{{ route('checkout.show', $plan) }}">@if ($plan->is_featured)<span class="sheen"></span>@endif {{ __('اشترك الآن') }}</a>
        </div>
      @endforeach
    </div>
    <p class="price-note">{{ $t('pricing', 'vat_note') }}</p>
  </section>

  {{-- ===== 15. faq ===== --}}
  <section id="faq">
    <div class="shead center rise">
      <span class="eyebrow" style="justify-content:center"><svg class="ico" aria-hidden="true"><use href="#i-help"/></svg>{{ __('الأسئلة الشائعة') }}</span>
      <h2>{{ __('أجوبةٌ صريحة قبل أن تبدأ') }}</h2>
    </div>
    <div class="glass detail faq rise">
      @foreach ($faqs as $i => $faq)
        <details class="acc" @if ($i === 0) open @endif>
          <summary><span class="qmark"><svg class="ico" aria-hidden="true"><use href="#i-help"/></svg></span>{{ $faq->question_ar }}<span class="cv"><svg class="ico" aria-hidden="true"><use href="#i-chevron"/></svg></span></summary>
          <div class="body">{{ $faq->answer_ar }}</div>
        </details>
      @endforeach
    </div>
  </section>

  {{-- ===== 16. final CTA ===== --}}
  <section style="padding-top:0">
    <div class="glass final rise">
      <h2>{{ __('ابدأ رحلتك في البحث الطبي اليوم') }}</h2>
      <p>{{ __('من أول محاضرة إلى شهادة الإتمام — مسارٌ منظَّم وفق المعايير، ومحاولاتٌ لا محدودة.') }}</p>
      <div class="cta-row">
        <a class="btn btn-gold" href="{{ auth()->check() ? route('dashboard') : route('register') }}"><span class="sheen"></span>{{ __('ابدأ الآن') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="btn btn-ghost" href="#program">{{ __('استعرض البرنامج') }}</a>
      </div>
    </div>
  </section>

  @push('seo')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            [
                '@type' => 'EducationalOrganization',
                '@id' => route('home').'#org',
                'name' => 'Research Track Platform',
                'alternateName' => 'مؤسسة ريستراك للتدريب',
                'url' => route('home'),
                'description' => $t('about', 'text'),
                'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'SA'],
                'identifier' => '7053567603',
            ],
            [
                '@type' => 'Course',
                'name' => $t('program', 'name', 'Research Track 1'),
                'description' => $t('program', 'about'),
                'provider' => ['@id' => route('home').'#org'],
                'inLanguage' => 'ar',
                'teaches' => $levels->pluck('name_ar')->all(),
                'offers' => $plans->map(fn ($p) => [
                    '@type' => 'Offer',
                    'price' => (string) (int) $p->price,
                    'priceCurrency' => 'SAR',
                    'category' => 'Subscription',
                ])->all(),
            ],
            $speakers->isEmpty() ? null : [
                '@type' => 'ItemList',
                'name' => $t('speakers', 'title', 'Our Speakers'),
                'itemListElement' => $speakers->values()->map(fn ($s, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'item' => array_filter([
                        '@type' => 'Person',
                        'name' => $s->name_ar,
                        'jobTitle' => $s->credential_ar ?: $s->title_ar,
                    ]),
                ])->all(),
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($f) => [
                    '@type' => 'Question',
                    'name' => $f->question_ar,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer_ar],
                ])->all(),
            ],
        ])),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
  @endpush
@endsection
