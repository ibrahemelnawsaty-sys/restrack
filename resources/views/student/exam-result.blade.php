@extends('layouts.app')

@section('title', 'نتيجة الاختبار — Restrack')

@section('content')
  @php($answers = $attempt->answers ?? [])
  <section class="page">
    <div class="glass tile rise in" style="text-align:center;padding:32px">
      <div class="chip {{ $attempt->passed ? 'teal' : 'coral' }}" style="margin-inline:auto;width:64px;height:64px;border-radius:18px">
        <svg class="ico" style="width:30px;height:30px" aria-hidden="true"><use href="#i-{{ $attempt->passed ? 'award' : 'infinity' }}"/></svg>
      </div>
      <h1 style="font-size:2.4rem;margin-top:14px" class="num">{{ $attempt->score }}%</h1>
      @if ($attempt->passed)
        <p class="badge ok" style="font-size:.85rem">مبروك — اجتزت المستوى!</p>
        <p style="color:var(--ink-2);margin-top:10px">تم إصدار شهادتك. تابع إلى المستوى التالي.</p>
      @else
        <p class="badge warn" style="font-size:.85rem">لم تجتز هذه المرة — حدّ النجاح {{ $attempt->level->pass_threshold }}%</p>
        <p style="color:var(--ink-2);margin-top:10px">لا بأس — <b>المحاولات غير محدودة</b>، وستأتيك أسئلة مختلفة في المحاولة القادمة.</p>
      @endif
      <div class="cta-row" style="justify-content:center;margin-top:18px">
        <a class="btn btn-gold" href="{{ route('exam.start', $attempt->level) }}"><span class="sheen"></span>إعادة المحاولة</a>
        <a class="btn btn-ghost" href="{{ route('levels.show', $attempt->level) }}">العودة للمستوى</a>
      </div>
    </div>

    <div class="shead rise in" style="margin-block:24px 14px"><h2 style="font-size:1.2rem;font-weight:800">مراجعة الإجابات</h2></div>
    @foreach ($questions as $i => $q)
      @php($chosen = $answers[$q->id] ?? null)
      <div class="glass q-card rise in">
        <div class="qh">{{ $i + 1 }}. {{ $q->question_ar }}</div>
        @foreach (($q->options_ar ?? []) as $idx => $opt)
          @php($isCorrect = (int) $idx === (int) $q->correct_index)
          @php($isChosen = ! is_null($chosen) && (int) $idx === (int) $chosen)
          @php($optStyle = $isCorrect ? 'border-color:var(--success);background:rgba(18,179,155,.10)' : ($isChosen ? 'border-color:#F0506E;background:rgba(240,80,110,.10)' : ''))
          @php($optColor = $isCorrect ? 'var(--success)' : ($isChosen ? '#F0506E' : 'var(--ink-3)'))
          @php($optIcon = $isCorrect ? 'check-s' : ($isChosen ? 'infinity' : 'chevron'))
          <div class="opt" style="cursor:default;{{ $optStyle }}">
            <svg class="ico" style="width:16px;height:16px;color:{{ $optColor }}" aria-hidden="true"><use href="#i-{{ $optIcon }}"></use></svg>
            <span>{{ $opt }}</span>
          </div>
        @endforeach
      </div>
    @endforeach
  </section>
@endsection
