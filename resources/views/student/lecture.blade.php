@extends('layouts.app')

@section('title', $lecture->title.' — Restrack')

@section('content')
  <section class="page">
    <div class="crumb rise in">
      <a href="{{ route('dashboard') }}">{{ __('لوحتي') }}</a><span>/</span>
      <a href="{{ route('levels.show', $level) }}">{{ $level->name }}</a><span>/</span>
      <span>{{ $lecture->title }}</span>
    </div>

    <div class="split" style="grid-template-columns:1.7fr .9fr;align-items:start">
      <div class="rise in">
        <div class="player" id="player">
          @if ($streamUrl)
            <video controls controlsList="nodownload" disablepictureinpicture preload="metadata" src="{{ $streamUrl }}"></video>
          @else
            <div style="text-align:center;color:var(--ink-3)">
              <span class="chip violet" style="margin-inline:auto"><svg class="ico" aria-hidden="true"><use href="#i-video"/></svg></span>
              <p style="margin-top:10px;font-size:.9rem">{{ __('سيُرفع الفيديو قريباً — البثّ محميّ داخل المنصة.') }}</p>
            </div>
          @endif
          <div class="wm" id="wm">{{ auth()->user()->name }} · <span class="num">{{ substr(sha1(auth()->id().config('app.key')), 0, 6) }}</span></div>
        </div>

        <h1 style="font-size:1.4rem;font-weight:800;margin-top:16px">{{ $lecture->title }}</h1>
        @if ($lecture->description)<p style="color:var(--ink-2);margin-top:6px">{{ $lecture->description }}</p>@endif

        <form method="POST" action="{{ route('lectures.progress', $lecture) }}" style="margin-top:14px">
          @csrf
          <input type="hidden" name="completed" value="1">
          <button type="submit" class="btn btn-gold-line btn-sm"><svg class="ico" aria-hidden="true"><use href="#i-check-s"/></svg>{{ __('وضع علامة كمكتملة') }}</button>
        </form>
      </div>

      <div class="glass tile rise in">
        <h3 style="margin-bottom:10px">{{ __('محاضرات :level', ['level' => $level->name]) }}</h3>
        <div class="lesson-list" style="display:grid;gap:4px">
          @foreach ($lectures as $lec)
            <a href="{{ route('lectures.show', $lec) }}" class="{{ $lec->id === $lecture->id ? 'active' : '' }}">
              <span style="flex:1">{{ $lec->title }}</span>
              <span class="num" style="color:var(--ink-3);font-size:.78rem">{{ $lec->duration_label }}</span>
            </a>
          @endforeach
        </div>
        <a class="btn btn-gold-line btn-sm full" href="{{ route('exam.start', $level) }}" style="margin-top:14px">{{ __('اختبار المستوى') }}</a>
      </div>
    </div>
  </section>

  @push('scripts')
    <script nonce="{{ Vite::cspNonce() }}">
      (function () {
        var wm = document.getElementById('wm');
        if (!wm) return;
        function move() {
          wm.style.top = (5 + Math.random() * 82).toFixed(1) + '%';
          wm.style.insetInlineStart = (5 + Math.random() * 68).toFixed(1) + '%';
          wm.style.opacity = (0.35 + Math.random() * 0.35).toFixed(2);
        }
        move();
        setInterval(move, 5000);
      })();
    </script>
  @endpush
@endsection
