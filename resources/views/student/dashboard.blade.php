@extends('layouts.app')

@section('title', __('لوحتي — Restrack'))

@section('content')
  @php
    $totalLectures = $levels->sum(fn ($l) => $l->lectures->count());
    $doneLectures = $completedLectureIds->count();
    $overall = $totalLectures ? (int) round($doneLectures / $totalLectures * 100) : 0;
  @endphp

  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-sparkle"/></svg>{{ __('لوحة الطالب') }}</span>
      <h1>{{ __('مرحباً، :name', ['name' => $user->name]) }}</h1>
      <p>{{ __('تابع رحلتك من المبتدئ إلى الباحث الناشر.') }}</p>
    </div>

    <div class="stat-row rise in">
      <div class="glass stat"><div class="v num">{{ $overall }}%</div><div class="k">{{ __('التقدّم الكلّي') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $passedLevelIds->count() }}/{{ $levels->count() }}</div><div class="k">{{ __('مستويات مجتازة') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $doneLectures }}</div><div class="k">{{ __('محاضرات مكتملة') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $certificates->count() }}</div><div class="k">{{ __('شهاداتي') }}</div></div>
    </div>

    <div class="shead rise in" style="margin-block:8px 18px"><h2 style="font-size:1.3rem;font-weight:800">{{ __('المستويات') }}</h2></div>
    <div class="grid-cards stagger rise in">
      @foreach ($levels as $level)
        @php
          $lecTotal = $level->lectures->count();
          $lecDone = $level->lectures->whereIn('id', $completedLectureIds)->count();
          $pct = $lecTotal ? (int) round($lecDone / $lecTotal * 100) : 0;
          $isPassed = $passedLevelIds->contains($level->id);
        @endphp
        <div class="glass sheen tile">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
            <span class="badge muted">{{ __('المستوى :n', ['n' => $level->sort_order]) }}</span>
            @if ($isPassed)<span class="badge ok"><svg class="ico" style="width:13px;height:13px" aria-hidden="true"><use href="#i-check-s"/></svg>{{ __('مجتاز') }}</span>@endif
          </div>
          <h3 style="margin-top:10px">{{ $level->name }}</h3>
          <p style="direction:ltr;text-align:start;color:var(--ink-3);font-size:.8rem">{{ $level->name_en }}</p>
          <div class="pbar" style="margin-top:14px"><i style="width:{{ $pct }}%"></i></div>
          <div style="display:flex;justify-content:space-between;color:var(--ink-3);font-size:.78rem;margin-top:6px">
            <span>{{ __(':done/:total محاضرة', ['done' => $lecDone, 'total' => $lecTotal]) }}</span><span class="num">{{ $pct }}%</span>
          </div>
          <div style="display:flex;gap:8px;margin-top:16px">
            <a class="btn btn-ghost btn-sm full" href="{{ route('levels.show', $level) }}">{{ __('متابعة') }}</a>
            <a class="btn btn-gold-line btn-sm full" href="{{ route('exam.start', $level) }}">{{ __('الاختبار') }}</a>
          </div>
        </div>
      @endforeach
    </div>

    @if ($certificates->isNotEmpty())
      <div class="shead rise in" style="margin-block:26px 18px"><h2 style="font-size:1.3rem;font-weight:800">{{ __('شهاداتي') }}</h2></div>
      <div class="grid-cards stagger rise in">
        @foreach ($certificates as $cert)
          <a class="glass sheen tile" href="{{ route('certificates.show', $cert) }}" style="text-decoration:none">
            <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-award"/></svg></div>
            <h3 style="margin-top:10px">{{ $cert->type === 'final' ? __('شهادة إكمال المسار') : __('شهادة ').optional($cert->level)->name }}</h3>
            <p class="num" style="color:var(--ink-3);font-size:.8rem;direction:ltr;text-align:start">{{ $cert->number }}</p>
          </a>
        @endforeach
      </div>
    @endif
  </section>
@endsection
