@extends('layouts.app')

@section('title', 'شهادة إكمال — Restrack')

@php
  $verifyUrl = route('certificates.verify', $certificate->verify_uuid);
  $qr = null;
  if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
      try {
          $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(132)->margin(1)->generate($verifyUrl);
      } catch (\Throwable $e) {
          $qr = null;
      }
  }
  $title = $certificate->type === 'final'
      ? 'Research Track Programs (1) — From Beginner to Expert in Medical Research'
      : optional($certificate->level)->name_en;
@endphp

@section('content')
  <section class="page certwrap">
    <div class="glass cert rise in" style="max-width:680px">
      <div class="rose"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
      <h3>Certificate of Completion</h3>
      <div class="ar">شهادة إكمال</div>
      <div class="who">{{ $certificate->user->name }}</div>
      <div class="desc">
        أتمّ بنجاح
        @if ($certificate->type === 'final')
          مسار «<span style="direction:ltr;unicode-bidi:isolate">{{ $title }}</span>»
        @else
          {{ optional($certificate->level)->name_ar }} — <span style="direction:ltr;unicode-bidi:isolate">{{ $title }}</span>
        @endif
      </div>

      <div style="display:flex;justify-content:center;margin-top:18px">
        @if ($qr)
          <div style="background:#fff;padding:8px;border-radius:12px;width:132px;height:132px">{!! $qr !!}</div>
        @else
          <div style="width:132px;height:132px;border-radius:12px;border:1px dashed var(--g-border);display:grid;place-items:center;color:var(--ink-3);font-size:.72rem;text-align:center">امسح للتحقّق</div>
        @endif
      </div>

      <div class="foot">
        <span class="num">{{ $certificate->number }}</span>
        <a class="qr" href="{{ $verifyUrl }}" style="text-decoration:none;color:var(--gold-ink)"><svg class="ico" aria-hidden="true"><use href="#i-globe"/></svg>تحقّق</a>
        <span class="num">{{ optional($certificate->issued_at)->format('Y / m / d') }}</span>
      </div>
    </div>

    <div class="cta-row" style="justify-content:center;margin-top:18px">
      <a class="btn btn-ghost" href="{{ route('certificates.index') }}">كل شهاداتي</a>
      <a class="btn btn-gold-line" href="{{ $verifyUrl }}"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>صفحة التحقّق العامة</a>
    </div>
  </section>
@endsection
