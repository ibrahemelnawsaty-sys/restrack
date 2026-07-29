@extends('layouts.app')

@section('title', ($level->exists ? 'تعديل' : 'إضافة').' مستوى — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ $level->exists ? __('تعديل مستوى') : __('مستوى جديد') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $level->exists ? route('admin.levels.update', $level) : route('admin.levels.store') }}" class="glass tile rise in">
      @csrf
      @if ($level->exists) @method('PUT') @endif

      <div class="split" style="grid-template-columns:1fr 1fr">
        <div class="field"><label>{{ __('الاسم (عربي)') }}</label><input name="name_ar" value="{{ old('name_ar', $level->name_ar) }}" required></div>
        <div class="field"><label>{{ __('الاسم (إنجليزي)') }}</label><input name="name_en" value="{{ old('name_en', $level->name_en) }}" dir="ltr" style="text-align:start" required></div>
        <div class="field"><label>{{ __('المُعرّف (slug)') }}</label><input name="slug" value="{{ old('slug', $level->slug) }}" dir="ltr" style="text-align:start" required></div>
        <div class="field"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $level->sort_order ?? 0) }}"></div>
        <div class="field"><label>{{ __('مجال التركيز (عربي)') }}</label><input name="focus_ar" value="{{ old('focus_ar', $level->focus_ar) }}"></div>
        <div class="field"><label>Focus (English)</label><input name="focus_en" value="{{ old('focus_en', $level->focus_en) }}" dir="ltr" style="text-align:start"></div>
        <div class="field"><label>{{ __('حدّ النجاح %') }}</label><input name="pass_threshold" type="number" min="1" max="100" value="{{ old('pass_threshold', $level->pass_threshold ?? 70) }}" required></div>
        <div class="field"><label>{{ __('عدد أسئلة الاختبار') }}</label><input name="exam_questions_count" type="number" min="1" max="100" value="{{ old('exam_questions_count', $level->exam_questions_count ?? 5) }}" required></div>
      </div>

      <div class="field"><label>{{ __('المواضيع (سطر لكل موضوع)') }}</label><textarea name="topics_ar" rows="5">{{ old('topics_ar', implode("\n", $level->topics_ar ?? [])) }}</textarea></div>

      <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $level->is_published ?? true))> {{ __('منشور') }}</label>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.levels.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
