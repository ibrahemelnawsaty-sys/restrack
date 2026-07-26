@extends('layouts.app')

@section('title', 'الاشتراك — '.$plan->name_ar.' — Restrack')

@section('content')
  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-wallet"/></svg>اشتراك واحد · كل المحتوى</span>
      <h1>تفاصيل البرنامج قبل الدفع</h1>
      <p>راجِع ما ستحصل عليه، ثم أكمِل الاشتراك.</p>
    </div>

    @if ($alreadySubscribed)
      <div class="alert rise in">لديك اشتراكٌ فعّال بالفعل — <a href="{{ route('dashboard') }}" style="color:var(--gold-ink);font-weight:700">اذهب إلى لوحتك</a>.</div>
    @endif

    <div class="split" style="grid-template-columns:1.5fr .9fr;align-items:start">
      <div class="glass tile rise in">
        <h3 style="margin-bottom:6px">ماذا يفتح لك هذا الاشتراك؟</h3>
        <p style="color:var(--ink-2);font-size:.9rem">المسار كامل بمستوياته، محاضرات مسجّلة، اختبارات بمحاولات لا محدودة، وشهادة إكمال.</p>
        <div style="margin-top:14px;display:grid;gap:12px">
          @foreach ($levels as $level)
            <div style="border-top:1px solid var(--g-border);padding-top:12px">
              <b>المستوى {{ $level->sort_order }} — {{ $level->name_ar }}</b>
              <span class="badge muted" style="margin-inline-start:6px">{{ $level->lectures->count() }} محاضرات</span>
              <p style="color:var(--ink-3);font-size:.84rem;margin-top:4px">{{ $level->focus_ar }} · ينتهي باختبار (70%، محاولات لا محدودة).</p>
            </div>
          @endforeach
        </div>
      </div>

      <div class="glass tile rise in" style="position:sticky;top:90px">
        <div class="pt" style="display:flex;justify-content:space-between;align-items:center">
          <b style="font-size:1.1rem">{{ $plan->name_ar }}</b>
          @if ($plan->is_featured)<span class="flag" style="font-size:.68rem;font-weight:800;color:#1a1405;background:linear-gradient(140deg,var(--gold-2),var(--gold-hi));border-radius:999px;padding:.2rem .6rem">الأوفر</span>@endif
        </div>
        <div class="amt" style="display:flex;align-items:baseline;gap:.35rem;margin-top:14px">
          <span class="num" style="font-size:2.4rem;font-weight:800">{{ (int) $plan->price }}</span>
          <span style="color:var(--ink-2)">ر.س</span>
          <span style="color:var(--ink-3);font-size:.85rem">/ {{ $plan->interval === 'annual' ? 'سنوياً' : 'شهرياً' }}</span>
        </div>
        <ul style="list-style:none;padding:0;margin:16px 0 0">
          @foreach (($plan->features_ar ?? []) as $f)
            <li style="display:flex;gap:.5rem;padding-block:6px;color:var(--ink-2);font-size:.88rem"><svg class="ico" style="width:17px;height:17px;color:var(--success)" aria-hidden="true"><use href="#i-check-s"/></svg>{{ $f }}</li>
          @endforeach
        </ul>
        <form method="POST" action="{{ route('checkout.process', $plan) }}" style="margin-top:16px">
          @csrf
          <button type="submit" class="btn btn-gold full"><span class="sheen"></span>ادفع واشترك</button>
        </form>
        <p style="color:var(--ink-3);font-size:.72rem;text-align:center;margin-top:10px">الدفع الآمن عبر Moyasar · شامل ضريبة القيمة المضافة 15%.</p>
      </div>
    </div>
  </section>
@endsection
