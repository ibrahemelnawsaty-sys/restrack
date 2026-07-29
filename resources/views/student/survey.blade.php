@extends('layouts.app')

@section('title', __('استبيان المستوى').' — '.$level->name_ar.' — Restrack')

@section('content')
  <section class="page">
    <div class="crumb rise in">
      <a href="{{ route('dashboard') }}">{{ __('لوحتي') }}</a><span>/</span>
      <a href="{{ route('levels.show', $level) }}">{{ $level->name_ar }}</a><span>/</span>
      <span>{{ __('الاستبيان') }}</span>
    </div>

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>{{ __('رأيك يطوّر المحتوى') }}</span>
      <h1>{{ __('استبيان') }} — {{ $level->name_ar }}</h1>
      <p>{{ __('دقيقة واحدة. نقرأ كل إجابة ونستخدمها في مراجعة المحتوى وتحديثه.') }}</p>
    </div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    @if ($existing)
      <div class="alert rise in">{{ __('سبق أن أرسلت رأيك في هذا المستوى — يمكنك تحديثه.') }}</div>
    @endif

    <form method="POST" action="{{ route('survey.store', $level) }}" class="glass tile rise in">
      @csrf

      @foreach (\App\Models\SurveyResponse::AXES as $key => $label)
        <fieldset class="rate">
          <legend>{{ $label }}</legend>
          <div class="rate-row">
            @for ($i = 1; $i <= 5; $i++)
              <label class="rate-opt">
                <input type="radio" name="{{ $key }}" value="{{ $i }}" required
                       @checked((int) old($key, $existing->{$key} ?? 0) === $i)>
                <span class="num">{{ $i }}</span>
              </label>
            @endfor
            <span class="rate-hint">{{ __('1 = ضعيف · 5 = ممتاز') }}</span>
          </div>
        </fieldset>
      @endforeach

      <div class="field" style="margin-top:18px">
        <label>{{ __('مقترحاتك (اختياري)') }}</label>
        <textarea name="notes" rows="4" placeholder="{{ __('ما الذي يمكن أن نحسّنه؟') }}">{{ old('notes', $existing->notes ?? '') }}</textarea>
      </div>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('إرسال') }}</button>
        <a class="btn btn-ghost" href="{{ route('levels.show', $level) }}">{{ __('لاحقاً') }}</a>
      </div>
    </form>
  </section>
@endsection
