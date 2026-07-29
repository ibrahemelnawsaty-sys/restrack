@extends('layouts.app')

@section('title', $level->name_ar.' — Restrack')

@section('content')
  <section class="page">
    <div class="crumb rise in">
      <a href="{{ route('dashboard') }}">{{ __('لوحتي') }}</a><span>/</span>
      <a href="{{ route('program.index') }}">{{ __('المسار') }}</a><span>/</span>
      <span>{{ $level->name_ar }}</span>
    </div>

    <div class="page-head rise in">
      <span class="badge muted">المستوى {{ $level->sort_order }}</span>
      @if ($passed)<span class="badge ok"><svg class="ico" style="width:13px;height:13px" aria-hidden="true"><use href="#i-check-s"/></svg>{{ __('مجتاز') }}</span>@endif
      <h1 style="margin-top:8px">{{ $level->name_ar }}</h1>
      <p>{{ $level->focus_ar }}</p>
    </div>

    <div class="split" style="align-items:start">
      <div class="glass tile rise in">
        {{-- owner note م8: "1/6" — how far along this level the learner is --}}
        <h3 style="margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;gap:10px">
          <span>{{ __('المحاضرات') }}</span>
          <span class="badge muted"><span class="num">{{ $level->lectures->whereIn('id', $completedLectureIds)->count() }}/{{ $level->lectures->count() }}</span> {{ __('محاضرة') }}</span>
        </h3>
        <div class="lesson-list" style="display:grid;gap:4px">
          @forelse ($level->lectures as $lec)
            <a href="{{ route('lectures.show', $lec) }}">
              <span class="chip violet" style="width:34px;height:34px;border-radius:10px">
                <svg class="ico" style="width:16px;height:16px" aria-hidden="true"><use href="#i-{{ $completedLectureIds->contains($lec->id) ? 'check-s' : 'video' }}"/></svg>
              </span>
              <span style="flex:1">{{ $lec->title_ar }}</span>
              <span class="num" style="color:var(--ink-3);font-size:.8rem">{{ $lec->duration_label }}</span>
            </a>
          @empty
            <p style="color:var(--ink-3);font-size:.85rem">{{ __('لا توجد محاضرات منشورة بعد.') }}</p>
          @endforelse
        </div>
      </div>

      <div class="glass tile rise in">
        <div class="chip gold"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg></div>
        <h3 style="margin-top:12px">{{ __('اختبار المستوى') }}</h3>
        <p style="color:var(--ink-2);font-size:.9rem;margin-top:6px">
          {{ $level->exam_questions_count }} أسئلة · حدّ النجاح {{ $level->pass_threshold }}% · <b>{{ __('محاولات لا محدودة') }}</b>.
        </p>
        @if ($bestAttempt)
          <p class="alert" style="margin-top:14px">{{ __('أفضل نتيجة سابقة:') }} <b class="num">{{ $bestAttempt->score }}%</b> — {{ $bestAttempt->passed ? __('ناجح') : __('حاوِل مجدداً') }}.</p>
        @endif
        <a class="btn btn-gold full" href="{{ route('exam.start', $level) }}" style="margin-top:12px"><span class="sheen"></span>{{ $bestAttempt ? __('إعادة الاختبار') : __('ابدأ الاختبار') }}</a>

        @if ($passed)
          {{-- owner note م12: the survey opens once the level is passed --}}
          <a class="btn btn-ghost full" href="{{ route('survey.show', $level) }}" style="margin-top:8px">{{ __('قيّم هذا المستوى') }}</a>
        @endif

        <div style="margin-top:14px">
          <div class="topics">
            @foreach (($level->topics_ar ?? []) as $t)<span>{{ $t }}</span>@endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
