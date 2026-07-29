@extends('layouts.app')

@section('title', ($faq->exists ? 'تعديل' : 'إضافة').' سؤال — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ $faq->exists ? __('تعديل سؤال') : __('سؤال جديد') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="glass tile rise in">
      @csrf
      @if ($faq->exists) @method('PUT') @endif

      <div class="field"><label>{{ __('السؤال (عربي)') }}</label><input name="question_ar" value="{{ old('question_ar', $faq->question_ar) }}" required></div>
      <div class="field"><label>{{ __('الإجابة (عربي)') }}</label><textarea name="answer_ar" rows="3" required>{{ old('answer_ar', $faq->answer_ar) }}</textarea></div>
      <div class="field"><label>{{ __('Question (English) — اختياري') }}</label><input name="question_en" value="{{ old('question_en', $faq->question_en) }}" dir="ltr" style="text-align:start"></div>
      <div class="field"><label>{{ __('Answer (English) — اختياري') }}</label><textarea name="answer_en" rows="3" dir="ltr" style="text-align:start">{{ old('answer_en', $faq->answer_en) }}</textarea></div>
      <div class="field" style="max-width:200px"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"></div>

      <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published ?? true))> {{ __('منشور') }}</label>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.faqs.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
