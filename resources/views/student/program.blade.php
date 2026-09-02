@extends('layouts.app')

@section('title', __('المسار الكامل — Restrack'))

@section('content')
  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg><span style="direction:ltr;unicode-bidi:isolate">Research Track Programs (1)</span></span>
      <h1>{{ __('المسار الكامل') }}</h1>
      <p>{{ __('من المبتدئ إلى الباحث الناشر — كل مستوى ينتهي باختبار، وإكماله يفتح التالي.') }}</p>
    </div>

    <div class="stagger rise in" style="display:grid;gap:16px">
      @foreach ($levels as $level)
        <div class="glass tile">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
            <div>
              <span class="badge muted">{{ __('المستوى :n', ['n' => $level->sort_order]) }}</span>
              @if ($passedLevelIds->contains($level->id))<span class="badge ok"><svg class="ico" style="width:13px;height:13px" aria-hidden="true"><use href="#i-check-s"/></svg>{{ __('مجتاز') }}</span>@endif
              <h3 style="margin-top:8px">{{ $level->name }} @if ($level->name !== $level->name_en)<span style="color:var(--ink-3);font-size:.85rem;direction:ltr;unicode-bidi:isolate">· {{ $level->name_en }}</span>@endif</h3>
              <p style="color:var(--ink-2);font-size:.9rem;margin-top:4px">{{ $level->focus }}</p>
            </div>
            <a class="btn btn-gold-line btn-sm" href="{{ route('exam.start', $level) }}"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ __('اختبار المستوى') }}</a>
          </div>

          <div class="lesson-list" style="margin-top:14px;display:grid;gap:4px">
            @forelse ($level->lectures as $lec)
              <a href="{{ route('lectures.show', $lec) }}">
                <span class="chip violet" style="width:34px;height:34px;border-radius:10px"><svg class="ico" style="width:16px;height:16px" aria-hidden="true"><use href="#i-video"/></svg></span>
                <span style="flex:1">{{ $lec->title }}</span>
                <span class="num" style="color:var(--ink-3);font-size:.8rem">{{ $lec->duration_label }}</span>
              </a>
            @empty
              <p style="color:var(--ink-3);font-size:.85rem">{{ __('لا توجد محاضرات منشورة بعد.') }}</p>
            @endforelse
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
