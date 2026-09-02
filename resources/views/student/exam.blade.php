@extends('layouts.app')

@section('title', __('اختبار :level — Restrack', ['level' => $level->name]))

@section('content')
  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ __('محاولات لا محدودة') }}</span>
      <h1>{{ __('اختبار: :level', ['level' => $level->name]) }}</h1>
      <p>{{ __(':num أسئلة · حدّ النجاح :pct% · أجِب عن كل الأسئلة ثم سلّم.', ['num' => $questions->count(), 'pct' => $level->pass_threshold]) }}</p>
    </div>

    <form method="POST" action="{{ route('exam.submit', $level) }}" class="rise in">
      @csrf
      <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

      @foreach ($questions as $i => $q)
        <div class="glass q-card">
          <div class="qh">{{ $i + 1 }}. {{ $q->question }}</div>
          @foreach ($q->options as $idx => $opt)
            <label class="opt">
              <input type="radio" name="answers[{{ $q->id }}]" value="{{ $idx }}">
              <span>{{ $opt }}</span>
            </label>
          @endforeach
        </div>
      @endforeach

      <button type="submit" class="btn btn-gold" style="margin-top:8px"><span class="sheen"></span>{{ __('إنهاء الاختبار وتسليمه') }}</button>
    </form>
  </section>
@endsection
