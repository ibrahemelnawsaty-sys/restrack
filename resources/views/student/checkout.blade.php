@extends('layouts.app')

@section('title', __('الاشتراك — :plan — Restrack', ['plan' => $plan->name]))

@section('content')
  <section class="page">
    <div class="page-head rise in" style="text-align:center;max-width:62ch;margin-inline:auto">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-wallet"/></svg>{{ __('اشتراك واحد · كل المحتوى') }}</span>
      <h1 style="margin-top:14px">{{ __('تفاصيل البرنامج قبل الدفع') }}</h1>
      <div class="sec-rule" style="margin-top:16px"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg></div>
      <p>{{ __('راجِع ما ستحصل عليه، ثم أكمِل الاشتراك.') }}</p>
    </div>

    @if ($alreadySubscribed)
      <div class="alert rise in" style="max-width:62ch;margin-inline:auto;text-align:center">{{ __('لديك اشتراكٌ فعّال بالفعل —') }} <a href="{{ route('dashboard') }}" style="color:var(--gold-ink);font-weight:700">{{ __('اذهب إلى لوحتك') }}</a>.</div>
    @endif

    <div class="split" style="margin-top:34px">
      <div class="glass tile rise in" style="padding:clamp(24px,3vw,32px);border-radius:20px">
        <h3 style="margin-bottom:0">{{ __('ماذا يفتح لك هذا الاشتراك؟') }}</h3>
        <span class="urule"></span>
        <p style="color:var(--ink-2);font-size:.93rem;margin-top:14px;line-height:1.9">{{ __('المسار كامل بمستوياته، محاضرات مسجّلة، اختبارات بمحاولات لا محدودة، وشهادة إتمام لكل مستوى تحمل درجتك.') }}</p>
        <div style="margin-top:20px;display:grid;gap:16px">
          @foreach ($levels as $level)
            <div style="display:flex;gap:14px;align-items:flex-start;border-top:1px solid var(--g-border);padding-top:16px">
              <span class="nbadge">{{ $level->sort_order }}</span>
              <div style="min-width:0">
                <b style="color:var(--ink)">{{ __('المستوى') }} {{ $level->sort_order }} — {{ $level->name }}</b>
                <span class="tchip" style="margin-inline-start:8px;vertical-align:middle"><span class="num">0/{{ $level->lectures->count() }}</span> {{ __('محاضرة') }}</span>
                <p style="color:var(--ink-3);font-size:.86rem;margin-top:8px;line-height:1.8">{{ $level->focus }} · {{ __('ينتهي باختبار') }} ({{ $level->pass_threshold }}%، {{ __('محاولات لا محدودة') }}).</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="glass tile rise in" style="position:sticky;inset-block-start:90px;padding:clamp(24px,3vw,32px);border-radius:20px">
        <div class="pt" style="display:flex;justify-content:space-between;align-items:center;gap:10px">
          <b style="font-size:1.15rem;color:var(--ink)">{{ $plan->name }}</b>
          @if ($plan->is_featured)<span class="flag" style="font-size:.68rem;font-weight:800;color:#1a1405;background:linear-gradient(140deg,var(--gold-2),var(--gold-hi));border-radius:999px;padding:.2rem .6rem">{{ __('الأوفر') }}</span>@endif
        </div>
        <span class="urule"></span>
        <div class="amt" style="display:flex;align-items:baseline;gap:.4rem;margin-top:20px;flex-wrap:wrap">
          <span class="num" style="font-size:clamp(2.6rem,6vw,3.2rem);font-weight:800;line-height:1;letter-spacing:-.02em;color:var(--ink)">{{ (int) $plan->price }}</span>
          <span style="color:var(--gold-ink);font-weight:700">{{ __('ر.س') }}</span>
          <span style="color:var(--ink-3);font-size:.85rem">/ {{ $plan->interval === 'annual' ? __('سنوياً') : __('شهرياً') }}</span>
        </div>
        <ul style="list-style:none;padding:16px 0 0;margin:20px 0 0;border-top:1px solid var(--g-border)">
          @foreach ($plan->features as $f)
            <li style="display:flex;gap:.6rem;align-items:flex-start;padding-block:7px;color:var(--ink-2);font-size:.9rem;line-height:1.7"><svg class="ico" style="width:18px;height:18px;flex:none;margin-top:4px;color:var(--gold-2)" aria-hidden="true"><use href="#i-check-s"/></svg><span>{{ $f }}</span></li>
          @endforeach
        </ul>
        {{-- Owner note م9: the learner must know attempts are unlimited BEFORE paying. --}}
        <p class="reassure">
          <svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>
          <span>{{ \App\Models\PageSection::text('home', 'pricing', 'note_unlimited', __('محاولات الاختبار غير محدودة. حدّ النجاح 70%، وتُطرح أسئلةٌ مختلفة في كل محاولة — لن تخسر ما دفعته إن لم تنجح من المرة الأولى.')) }}</span>
        </p>
        @if (app(\App\Services\PaymentService::class)->isTestMode())
          <p class="badge warn" style="display:block;margin-top:16px;font-size:.78rem;line-height:1.6">
            {{ __('وضع الاختبار: بوابة الدفع مضبوطة على مفاتيح تجريبية، فلا تُخصم أموال حقيقية. لا تتركه مفعّلاً على موقع معلَن.') }}
          </p>
        @endif
        <form method="POST" action="{{ route('checkout.process', $plan) }}" style="margin-top:20px">
          @csrf
          <button type="submit" class="btn btn-gold full"><span class="sheen"></span>{{ __('ادفع واشترك') }}</button>
        </form>
        <p style="color:var(--ink-3);font-size:.74rem;text-align:center;margin-top:12px;line-height:1.7">{{ __('الدفع الآمن عبر Paymob · شامل ضريبة القيمة المضافة 15%.') }}</p>
      </div>
    </div>
  </section>
@endsection
