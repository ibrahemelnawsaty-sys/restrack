@extends('layouts.app')

@section('title', __('شهادة إكمال — Restrack'))

@php
  $verifyUrl = route('certificates.verify', $certificate->verify_uuid);
  $qr = null;
  if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
      try {
          $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(132)->margin(1)->generate($verifyUrl);
          // the library prepends an XML prolog, which is not valid inside an HTML document
          $qr = preg_replace('/<\?xml.*?\?>\s*/s', '', (string) $qr);
      } catch (\Throwable $e) {
          $qr = null;
      }
  }
  $title = $certificate->type === 'final'
      ? 'Research Track Programs (1) — From Beginner to Expert in Medical Research'
      : optional($certificate->level)->name_en;
  // The track line is bilingual on the sheet itself, whatever the UI locale.
  $track = $certificate->type === 'final'
      ? $title
      : trim(trim((string) optional($certificate->level)->name_ar).' — '.trim((string) $title), ' —');
  // Signature hook — the owner drops the director's transparent PNG at
  // public/images/signature.png and it appears above the ruled line here AND in the PDF.
  // Until then the ruled line + job title stands in for it; no signature is fabricated.
  $signature = is_file(public_path('images/signature.png')) ? asset('images/signature.png') : null;
@endphp

@section('content')
  <section class="page certwrap">
    <article class="csheet rise in">
      <span class="cnr tl"></span><span class="cnr tr"></span><span class="cnr bl"></span><span class="cnr br"></span>
      <span class="cframe"></span>

      <div class="cbody">
        <div class="cbrand">
          {{-- the 3-bar brand mark, with its own gradient id so it never depends on the navbar's --}}
          <svg class="clogo" viewBox="0 0 40 40" aria-hidden="true">
            <defs><linearGradient id="certlg" x1="0" y1="1" x2="1" y2="0">
              <stop offset="0" stop-color="#B9932F"/><stop offset="1" stop-color="#8B7CFF"/>
            </linearGradient></defs>
            <rect x="6" y="22" width="6" height="12" rx="3" fill="url(#certlg)"/>
            <rect x="17" y="13" width="6" height="21" rx="3" fill="url(#certlg)"/>
            <rect x="28" y="5" width="6" height="29" rx="3" fill="url(#certlg)"/>
          </svg>
          <b>Restrack</b>
          <span>{{ __('مؤسسة ريستراك للتدريب') }}</span>
        </div>

        <h1 class="ctitle">CERTIFICATE</h1>
        <div class="csub">OF COMPLETION</div>
        <div class="car">{{ __('شهادة إكمال') }}</div>

        <div class="cflour" aria-hidden="true"><i></i><b></b><i></i></div>

        <p class="clead">{{ __('نشهد بأن') }}</p>
        <div class="cname">{{ $certificate->user->name }}</div>

        <p class="clead">{{ __('أتمّ بنجاح') }}</p>
        <div class="ctrack" dir="auto">{{ $track }}</div>

        <div class="cmeta">
          <span class="cchip">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
            {{ __('تاريخ الإكمال') }}
            <b class="num">{{ optional($certificate->issued_at)->format('Y / m / d') }}</b>
          </span>
          {{-- owner note م10: the certificate states the score it was earned with --}}
          @if ($certificate->score !== null)
            <span class="cchip">{{ __('بدرجة') }}<b class="num">{{ rtrim(rtrim(number_format((float) $certificate->score, 2, '.', ''), '0'), '.') }}%</b></span>
          @endif
        </div>

        <div class="cbottom">
          <div class="cseal">
            <svg viewBox="0 0 120 122" aria-hidden="true">
              <defs><linearGradient id="certseal" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="var(--c-gold-2)"/><stop offset=".55" stop-color="var(--c-gold)"/><stop offset="1" stop-color="var(--c-gold-2)"/>
              </linearGradient></defs>
              <path d="M50 88 L40 118 L54 110 L60 118 L60 88 Z" fill="var(--c-gold)" opacity=".55"/>
              <path d="M70 88 L80 118 L66 110 L60 118 L60 88 Z" fill="var(--c-gold)" opacity=".8"/>
              <polygon fill="url(#certseal)" points="60,12 65,18.3 71.4,13.5 74.5,20.9 82,17.9 83.1,25.9 91.1,24.9 90.1,32.9 98.1,34 95.1,41.5 102.5,44.6 97.7,51 104,56 97.7,61 102.5,67.4 95.1,70.5 98.1,78 90.1,79.1 91.1,87.1 83.1,86.1 82,94.1 74.5,91.1 71.4,98.5 65,93.7 60,100 55,93.7 48.6,98.5 45.5,91.1 38,94.1 36.9,86.1 28.9,87.1 29.9,79.1 21.9,78 24.9,70.5 17.5,67.4 22.3,61 16,56 22.3,51 17.5,44.6 24.9,41.5 21.9,34 29.9,32.9 28.9,24.9 36.9,25.9 38,17.9 45.5,20.9 48.6,13.5 55,18.3"/>
              <circle cx="60" cy="56" r="34" fill="none" stroke="var(--c-paper)" stroke-width="2"/>
              <circle cx="60" cy="56" r="27" fill="var(--c-panel)"/>
              <polygon fill="url(#certseal)" points="60,30 63.4,45.5 75.3,35 68.9,49.5 84.7,48 71,56 84.7,64 68.9,62.5 75.3,77 63.4,66.5 60,82 56.6,66.5 44.7,77 51.1,62.5 35.3,64 49,56 35.3,48 51.1,49.5 44.7,35 56.6,45.5"/>
            </svg>
            <small dir="auto">{{ __('ختم المؤسسة') }}</small>
          </div>

          <div class="cqr">
            @if ($qr)
              <div class="box">{!! $qr !!}</div>
            @else
              <div class="box" style="display:grid;place-items:center;background:transparent;border-style:dashed;color:var(--c-ink-2);font-size:.68rem;text-align:center">{{ __('امسح للتحقّق') }}</div>
            @endif
            <small dir="auto">{{ __('امسح للتحقّق') }}</small>
          </div>

          <div class="csign">
            @if ($signature)
              <img class="sigimg" src="{{ $signature }}" alt="">
            @endif
            <div class="rule"></div>
            <b dir="auto">{{ __('مدير التدريب') }}</b>
            <span>Director of Training</span>
          </div>
        </div>

        <div class="cfoot">
          <span class="num" style="direction:ltr;unicode-bidi:isolate">{{ $certificate->number }}</span>
          <span>{{ __('الرقم الموحّد') }} <span class="num" style="direction:ltr;unicode-bidi:isolate">7053567603</span></span>
          <a href="{{ $verifyUrl }}">{{ __('صفحة التحقّق العامة') }}</a>
        </div>
      </div>
    </article>

    <div class="cta-row" style="justify-content:center;margin-top:18px">
      <a class="btn btn-gold" href="{{ route('certificates.download', $certificate) }}"><span class="sheen"></span><svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg>{{ __('تحميل PDF') }}</a>
      <a class="btn btn-ghost" href="{{ route('certificates.index') }}">{{ __('كل شهاداتي') }}</a>
      <a class="btn btn-gold-line" href="{{ $verifyUrl }}"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>{{ __('صفحة التحقّق العامة') }}</a>
    </div>
  </section>

  {{-- reached only when the server has no PDF engine: print-to-PDF straight from the browser --}}
  @if (session('print'))
    @push('scripts')
      <script nonce="{{ Vite::cspNonce() }}">window.addEventListener('load', function () { window.print(); });</script>
    @endpush
  @endif
@endsection
