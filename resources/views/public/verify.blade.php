@extends('layouts.app')

@section('title', __('التحقّق من الشهادة — Restrack'))

@section('content')
  <section class="page certwrap">
    @if ($certificate)
      <div class="glass cert rise in" style="max-width:620px">
        <div class="rose" style="color:var(--success)"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></div>
        <span class="badge ok" style="font-size:.82rem">{{ __('شهادة صحيحة وموثّقة') }}</span>
        <div class="who" style="margin-top:14px">{{ $certificate->user->name }}</div>
        <div class="desc">
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
        <div class="foot">
          <span class="num" style="direction:ltr;unicode-bidi:isolate">{{ $certificate->number }}</span>
          <span>{{ __('Certificate of Completion / شهادة إكمال') }}</span>
          <span class="num">{{ optional($certificate->issued_at)->format('Y / m / d') }}</span>
        </div>
      </div>
    @else
      <div class="glass tile rise in" style="text-align:center;max-width:520px;padding:34px">
        <div class="chip coral" style="margin-inline:auto"><svg class="ico" aria-hidden="true"><use href="#i-help"/></svg></div>
        <h1 style="font-size:1.4rem;margin-top:12px">{{ __('لا توجد شهادة بهذا الرمز') }}</h1>
        <p style="color:var(--ink-2);margin-top:8px">{{ __('تأكّد من الرابط أو رمز التحقّق:') }} <span class="num" style="direction:ltr;unicode-bidi:isolate">{{ $uuid }}</span></p>
        <a class="btn btn-ghost" href="{{ route('home') }}" style="margin-top:16px">{{ __('العودة للرئيسية') }}</a>
      </div>
    @endif
  </section>
@endsection
