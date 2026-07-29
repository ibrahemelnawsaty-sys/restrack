@extends('layouts.app')

@section('title', __('Restrack — Research Track Platform · منصة البحث الطبي'))

@section('content')
  {{-- ===== hero ===== --}}
  <section class="hero">
    <div class="hero-grid">
      <div class="rise in">
        <span class="kick glass"><span class="dot"></span>{{ __('محاضرات مسجّلة · محتوى محميّ · محاولات لا محدودة') }}</span>
        <h1><span class="grad-gold">{{ \App\Models\PageSection::text('home', 'hero', 'highlight', 'Research Track Platform') }}</span></h1>
        <p class="sub">{{ \App\Models\PageSection::text('home', 'hero', 'subtitle', 'From Beginner to Expert in Medical Research') }}</p>
        <p class="lead">{{ \App\Models\PageSection::text('home', 'hero', 'lead', __('منصة عربية فاخرة لإتقان البحث الطبي — اشتراكٌ واحد يفتح المسار كاملاً من المبتدئ إلى الباحث الناشر.')) }}</p>
        <div class="cta-row">
          <a class="btn btn-gold" href="{{ auth()->check() ? route('dashboard') : route('register') }}"><span class="sheen"></span>{{ __('ابدأ رحلتك') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="btn btn-ghost" href="#program">{{ __('استعرض البرنامج') }}</a>
        </div>
        <div class="trust">
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>{{ __('محتوى محميّ بعلامة مائية') }}</span>
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-bolt"/></svg>{{ __('خفيف وسريع') }}</span>
          <span class="tpill"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg>{{ __('شهادة إكمال') }}</span>
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
  </section>

  {{-- ===== stats band ===== --}}
  <div class="glass band rise" style="margin-block:6px 12px">
    <div class="band-grid">
      <div class="b"><div class="n num" data-count="{{ $levels->count() }}">{{ $levels->count() }}</div><div class="l">{{ __('مستويات ممتحَنة') }}</div></div>
      <div class="b"><div class="n num" data-count="70" data-suffix="%">70%</div><div class="l">{{ __('حدّ النجاح') }}</div></div>
      <div class="b"><div class="n"><svg viewBox="0 0 24 24" role="img" aria-label="{{ __('بلا حدود') }}" style="width:2.1rem;height:2.1rem;display:block;margin-inline:auto;fill:none;stroke:url(#gg);stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round"><use href="#i-infinity"/></svg></div><div class="l">{{ __('محاولات الاختبار') }}</div></div>
      <div class="b"><div class="n num" data-count="100" data-suffix="%">100%</div><div class="l">{{ __('داخل المنصة') }}</div></div>
    </div>
  </div>

  {{-- ===== features ===== --}}
  <section id="features">
    <div class="shead rise">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-sparkle"/></svg>{{ __('لماذا ريستراك') }}</span>
      <h2>{{ __('تجربة تعليمية فاخرة بكل التفاصيل') }}</h2>
      <p>{{ __('أيقونات دقيقة، زجاج راقٍ، وحركة انسيابية — كل عنصر مصمَّم ليشعر الطالب أنه في منتجٍ عالمي.') }}</p>
    </div>
    <div class="fgrid stagger rise">
      <div class="glass sheen fcard"><div class="chip violet"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg></div><h3>{{ __('محاضرات مسجّلة') }}</h3><p>{{ __('شاهد وأعِد متى شئت، بجودةٍ تتكيّف مع شبكتك — دون تقطيع ولا انتظار.') }}</p></div>
      <div class="glass sheen fcard"><div class="chip teal"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg></div><h3>{{ __('محاولات لا محدودة') }}</h3><p>{{ __('اختبِر بلا خوف — النجاح 70%، وأعِد المحاولة كما تشاء حتى تُتقِن.') }}</p></div>
      <div class="glass sheen fcard"><div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></div><h3>{{ __('محتوى محميّ') }}</h3><p>{{ __('بثٌّ محميّ داخل المنصة وعلامةٌ مائية باسمك — أي تسريبٍ متتبَّعٌ لصاحبه.') }}</p></div>
      <div class="glass sheen fcard"><div class="chip teal"><svg class="ico" aria-hidden="true"><use href="#i-research"/></svg></div><h3>{{ __('تعلّم بالممارسة') }}</h3><p>{{ __('احسب حجم العيّنة، قيّم ورقةً بحثية، اقرأ مخطّط الغابة — بتغذيةٍ راجعة فورية.') }}</p></div>
      <div class="glass sheen fcard"><div class="chip violet"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div><h3>{{ __('شهادات موثّقة') }}</h3><p>{{ __('ثنائية اللغة بتحقّق QR، وتُشارَك على لينكدإن وواتساب بضغطةٍ واحدة.') }}</p></div>
      <div class="glass sheen fcard"><div class="chip coral"><svg class="ico" aria-hidden="true"><use href="#i-bolt"/></svg></div><h3>{{ __('سريعة جداً') }}</h3><p>{{ __('مبنيّة لتكون خفيفة على كل جهاز — حتى على الشبكات الضعيفة.') }}</p></div>
    </div>
  </section>

  {{-- ===== program: level ladder (DB-driven) ===== --}}
  <section id="program">
    <div class="shead center rise">
      <span class="eyebrow" style="justify-content:center"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg><span style="direction:ltr;unicode-bidi:isolate">Research Track Programs (1)</span></span>
      <h2>{{ __('من المبتدئ إلى الباحث الناشر') }}</h2>
      <p>{{ __('سُلّمٌ واحد بمستويات ممتحَنة — كل مستوى ينتهي باختبار، وإكمال المسار يمنحك الشهادة.') }}</p>
    </div>

    <div class="ladder stagger rise">
      @foreach ($levels as $level)
        <div class="glass sheen lvl">
          <div class="node num">{{ $level->sort_order }}</div>
          <div class="en">{{ $level->name_en }}</div>
          <div class="ar">{{ $level->name_ar }}</div>
          <div class="focus">{{ $level->focus_ar }}</div>
          <div class="topics">
            @foreach (array_slice($level->topics_ar ?? [], 0, 5) as $topic)
              <span>{{ $topic }}</span>
            @endforeach
          </div>
          <div class="exam"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ __('ينتهي باختبار · محاولات لا محدودة') }}</div>
        </div>
      @endforeach
    </div>

    <div class="glass cap rise">
      <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
      <div><b>{{ __('إكمال المسار = شهادة إكمال') }}</b><p>{{ __('بعد اجتياز جميع المستويات تحصل على شهادة إكمال ثنائية اللغة بتحقّق QR.') }}</p></div>
    </div>
  </section>

  {{-- ===== program detail ===== --}}
  <section style="padding-top:0">
    <div class="glass detail rise">
      <div class="shead" style="margin-bottom:18px">
        <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg>{{ __('اعرف قبل أن تدفع') }}</span>
        <h2 style="font-size:clamp(1.4rem,2.8vw,1.9rem)">{{ __('تفاصيل البرنامج كاملة') }}</h2>
      </div>
      <details class="acc" open>
        <summary><span class="qmark"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg></span>{{ __('ماذا يتضمّن كل مستوى؟') }}<span class="cv"><svg class="ico" aria-hidden="true"><use href="#i-chevron"/></svg></span></summary>
        <div class="body">
          {{ __('كل مستوى مجموعةٌ من المحاضرات القصيرة —') }} <b>{{ __('كلها مسجّلة ويمكن إعادة مشاهدتها في أي وقت') }}</b> {{ __('— يتبعها اختبار مُصحَّح آلياً يفتح المستوى التالي.') }}
          <ul>
            @foreach ($levels as $level)
              <li>{{ $level->name_ar }} — {{ $level->focus_ar }} ({{ $level->lectures->count() }} محاضرات).</li>
            @endforeach
          </ul>
        </div>
      </details>
      <details class="acc">
        <summary><span class="qmark"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg></span>{{ __('ماذا لو دفعتُ ورسبت في الاختبار؟') }}<span class="cv"><svg class="ico" aria-hidden="true"><use href="#i-chevron"/></svg></span></summary>
        <div class="body">{{ __('لا داعي للقلق —') }} <b>{{ __('محاولات الاختبار غير محدودة') }}</b>{{ __('. حدّ النجاح 70%، وتُطرح أسئلة مختلفة في كل محاولة من بنك أسئلةٍ متجدّد.') }}</div>
      </details>
      <details class="acc">
        <summary><span class="qmark"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></span>{{ __('ماذا أحصل عليه في النهاية؟') }}<span class="cv"><svg class="ico" aria-hidden="true"><use href="#i-chevron"/></svg></span></summary>
        <div class="body">{{ __('شهادة إكمال (') }}<bdi>Certificate of Completion</bdi> {{ __('/ شهادة إكمال) ثنائية اللغة، برقمٍ فريد وصفحة تحقّق عامة عبر') }} <bdi>QR</bdi>.</div>
      </details>
    </div>
  </section>

  {{-- ===== courses + protection ===== --}}
  <section style="padding-top:0">
    <div class="split">
      <div class="stagger rise">
        @foreach ($levels as $level)
          @php($lec = $level->lectures->first())
          @if ($lec)
            <div class="glass sheen course" style="margin-bottom:14px">
              <div class="poster"><span class="play"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg></span></div>
              <div class="m">
                <span class="lvl-tag"><svg class="ico" style="width:12px;height:12px" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $level->name_ar }}</span>
                <b>{{ $lec->title_ar }}</b>
                <div class="meta"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg>{{ $lec->duration_label }} دقيقة · مسجّلة</div>
              </div>
            </div>
          @endif
        @endforeach
      </div>

      <div class="glass prot rise">
        <div class="top"><div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></div><h3>{{ __('حماية المحتوى — بطبقات') }}</h3></div>
        <p style="color:var(--ink-2);font-size:.9rem;margin-bottom:6px">{{ __('كل الفيديوهات داخل المنصة، لا روابط خارجية.') }}</p>
        <div class="layer"><span class="li"><svg class="ico" aria-hidden="true"><use href="#i-key"/></svg></span><div><b>{{ __('روابط موقّعة قصيرة الأمد') }}</b><p>{{ __('تنتهي خلال دقائق — الرابط المسرَّب يموت قبل أن يُشارَك.') }}</p></div></div>
        <div class="layer"><span class="li"><svg class="ico" aria-hidden="true"><use href="#i-water"/></svg></span><div><b>{{ __('علامة مائية باسم الطالب') }}</b><p>{{ __('تتحرّك فوق الفيديو وتظهر في أي تسجيل — تربط التسريب بصاحبه.') }}</p></div></div>
        <div class="layer"><span class="li"><svg class="ico" aria-hidden="true"><use href="#i-monitor"/></svg></span><div><b>{{ __('حدّ الأجهزة والجلسات') }}</b><p>{{ __('عددٌ محدود من الأجهزة المتزامنة مع كشف السلوك غير المعتاد.') }}</p></div></div>
      </div>
    </div>
  </section>

  {{-- ===== pricing (DB-driven) ===== --}}
  <section id="pricing">
    <div class="shead center rise">
      <span class="eyebrow" style="justify-content:center"><svg class="ico" aria-hidden="true"><use href="#i-wallet"/></svg>{{ __('اشتراك واحد · كل المحتوى') }}</span>
      <h2>{{ __('خطة واحدة تفتح المسار كاملاً') }}</h2>
      <p>{{ __('لا شراء لكل دورة على حدة — اشتراكٌ واحد يمنحك كل المستويات والمحاضرات والاختبارات والشهادة.') }}</p>
    </div>

    <div class="plans stagger rise">
      @foreach ($plans as $plan)
        <div class="glass sheen plan @if ($plan->is_featured) feat @endif">
          <div class="pt"><b>{{ $plan->name_ar }}</b>@if ($plan->is_featured)<span class="flag">{{ __('الأوفر') }}</span>@endif</div>
          <div class="amt"><span class="num n">{{ (int) $plan->price }}</span><span class="cur">{{ __('ر.س') }}</span><span class="per">/ {{ $plan->interval === 'annual' ? __('سنوياً') : __('شهرياً') }}</span></div>
          <ul>
            @foreach (($plan->features_ar ?? []) as $feature)
              <li><svg class="ico" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $feature }}</li>
            @endforeach
          </ul>
          <a class="btn {{ $plan->is_featured ? 'btn-gold' : 'btn-ghost' }} full" href="{{ route('checkout.show', $plan) }}">@if ($plan->is_featured)<span class="sheen"></span>@endif اشترك الآن</a>
        </div>
      @endforeach
    </div>
    <p class="price-note">{{ __('الأرقام توضيحية وتُحدَّد لاحقاً · الأسعار النهائية شاملة ضريبة القيمة المضافة 15% مع فاتورة ZATCA.') }}</p>
  </section>

  {{-- ===== certificate showcase ===== --}}
  <section class="certwrap">
    <div class="glass cert rise">
      <div class="rose"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
      <h3>Certificate of Completion</h3>
      <div class="ar">{{ __('شهادة إكمال') }}</div>
      <div class="who">{{ __('عبدالله محمد الأحمدي') }}</div>
      <div class="desc">{{ __('أتمّ بنجاح مسار «Research Track Programs (1) — From Beginner to Expert in Medical Research»') }}</div>
      <div class="foot"><span class="num">No. RST-2026-4F9A2C1B</span><span class="qr"><svg class="ico" aria-hidden="true"><use href="#i-globe"/></svg>{{ __('تحقّق عبر QR') }}</span><span class="num">2026 / 07 / 24</span></div>
    </div>
  </section>

  {{-- ===== faq (DB-driven) ===== --}}
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

  {{-- ===== final CTA ===== --}}
  <section style="padding-top:0">
    <div class="glass final rise">
      <h2>{{ __('ابدأ رحلتك في البحث الطبي اليوم') }}</h2>
      <p>{{ __('من أول محاضرة إلى شهادة الإكمال — مسارٌ واضح، محتوىً محميّ، ومحاولاتٌ لا محدودة.') }}</p>
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
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f->question_ar,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer_ar],
        ])->all(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
  @endpush
@endsection
