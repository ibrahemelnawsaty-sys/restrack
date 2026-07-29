@extends('layouts.app')

@section('title', __('شهاداتي — Restrack'))

@section('content')
  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg>{{ __('الشهادات') }}</span>
      <h1>{{ __('شهاداتي') }}</h1>
      <p>{{ __('شهادات موثّقة بتحقّق QR — قابلة للمشاركة على لينكدإن وواتساب.') }}</p>
    </div>

    @if ($certificates->isEmpty())
      <div class="glass tile rise in" style="text-align:center;padding:34px">
        <p style="color:var(--ink-2)">{{ __('لا توجد شهادات بعد. اجتَز اختبار أي مستوى (70%) لتحصل على أول شهادة.') }}</p>
        <a class="btn btn-gold" href="{{ route('program.index') }}" style="margin-top:14px"><span class="sheen"></span>{{ __('ابدأ المسار') }}</a>
      </div>
    @else
      <div class="grid-cards stagger rise in">
        @foreach ($certificates as $cert)
          <a class="glass sheen tile" href="{{ route('certificates.show', $cert) }}" style="text-decoration:none">
            <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
            <h3 style="margin-top:10px">{{ $cert->type === 'final' ? __('شهادة إكمال المسار') : __('شهادة ').optional($cert->level)->name_ar }}</h3>
            <p class="num" style="color:var(--ink-3);font-size:.8rem;direction:ltr;text-align:start">{{ $cert->number }}</p>
            <p style="color:var(--ink-3);font-size:.78rem;margin-top:4px">{{ optional($cert->issued_at)->format('Y / m / d') }}</p>
          </a>
        @endforeach
      </div>
    @endif
  </section>
@endsection
