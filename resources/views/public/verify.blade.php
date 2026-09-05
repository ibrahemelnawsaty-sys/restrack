@extends('layouts.app')

@section('title', __('التحقّق من الشهادة — Restrack'))

@section('content')
  <section class="page certwrap">
    @if ($certificate)
      <div class="glass cert rise in" style="max-width:640px;padding:clamp(32px,5vw,48px)">
        <span class="dots s" aria-hidden="true"></span>
        <span class="dots e" aria-hidden="true"></span>

        <div class="rose" style="color:var(--success)"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></div>
        <span class="badge ok" style="font-size:.82rem;margin-top:14px">{{ __('شهادة صحيحة وموثّقة') }}</span>

        <div class="sec-rule plain" style="margin-top:18px"><span class="dot"></span></div>

        <div class="who" style="margin-top:16px;font-size:clamp(1.35rem,3.4vw,1.7rem)">{{ $certificate->user->name }}</div>
        <div class="desc" style="margin-top:10px;font-size:.95rem;line-height:1.9">
          @if ($certificate->type === 'final')
            {{ __('أتمّ بنجاح مسار') }} «<span style="direction:ltr;unicode-bidi:isolate">Research Track Programs (1)</span>»
          @else
            {{ __('اجتاز') }} {{ optional($certificate->level)->name }}
          @endif
          — {{ __('مؤسسة ريستراك للتدريب.') }}
        </div>
        @if ($certificate->score !== null)
          <div class="cert-score"><span>{{ __('بدرجة') }}</span><b class="num">{{ rtrim(rtrim(number_format((float) $certificate->score, 2, '.', ''), '0'), '.') }}%</b></div>
        @endif
        <div class="foot" style="margin-top:28px">
          <span class="num" style="direction:ltr;unicode-bidi:isolate">{{ $certificate->number }}</span>
          <span>{{ __('Certificate of Completion / شهادة إكمال') }}</span>
          <span class="num">{{ optional($certificate->issued_at)->format('Y / m / d') }}</span>
        </div>
      </div>
    @else
      <div class="glass tile rise in" style="text-align:center;max-width:540px;padding:clamp(32px,5vw,44px);border-radius:20px">
        <div class="chip coral" style="margin-inline:auto"><svg class="ico" aria-hidden="true"><use href="#i-help"/></svg></div>
        <h1 style="font-size:clamp(1.3rem,3vw,1.55rem);margin-top:16px">{{ __('لا توجد شهادة بهذا الرمز') }}</h1>
        <div class="sec-rule plain" style="margin-top:18px"><span class="dot"></span></div>
        <p style="color:var(--ink-2);margin-top:12px;font-size:.95rem">{{ __('تأكّد من الرابط أو رمز التحقّق:') }} <span class="num" style="direction:ltr;unicode-bidi:isolate">{{ $uuid }}</span></p>
        <a class="btn btn-ghost" href="{{ route('home') }}" style="margin-top:22px">{{ __('العودة للرئيسية') }}</a>
      </div>
    @endif
  </section>
@endsection
