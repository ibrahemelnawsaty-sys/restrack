@extends('layouts.app')

@section('title', 'اختبار '.$level->name_ar.' — Restrack')

@section('content')
  <section class="page">
    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-infinity"/></svg>{{ __('محاولات لا محدودة') }}</span>
      <h1>اختبار: {{ $level->name_ar }}</h1>
      <p>{{ $questions->count() }} أسئلة · حدّ النجاح {{ $level->pass_threshold }}% · أجِب عن كل الأسئلة ثم سلّم.</p>
    </div>

    <form method="POST" action="{{ route('exam.submit', $level) }}" class="rise in">
      @csrf
      <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

      @foreach ($questions as $i => $q)
        <div class="glass q-card">
          <div class="qh">{{ $i + 1 }}. {{ $q->question_ar }}</div>
          @foreach (($q->options_ar ?? []) as $idx => $opt)
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
