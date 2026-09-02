@extends('layouts.app')

@section('title', ($lecture->exists ? 'تعديل' : 'إضافة').' محاضرة — المدرّب')

@section('content')
  <section class="page">
    @include('instructor._nav')

    <div class="page-head rise in"><h1>{{ $lecture->exists ? __('تعديل محاضرة') : __('محاضرة جديدة') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $lecture->exists ? route('instructor.lectures.update', $lecture) : route('instructor.lectures.store') }}" class="glass tile rise in" enctype="multipart/form-data">
      @csrf
      @if ($lecture->exists) @method('PUT') @endif

      <div class="split" style="grid-template-columns:1fr 1fr">
        <div class="field">
          <label>{{ __('المستوى') }}</label>
          <select name="level_id" required>
            @foreach ($levels as $l)<option value="{{ $l->id }}" @selected(old('level_id', $lecture->level_id) == $l->id)>{{ $l->name }}</option>@endforeach
          </select>
        </div>
        <div class="field"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $lecture->sort_order ?? 0) }}"></div>
        <div class="field"><label>{{ __('العنوان (عربي)') }}</label><input name="title_ar" value="{{ old('title_ar', $lecture->title_ar) }}" required></div>
        <div class="field"><label>Title (English)</label><input name="title_en" value="{{ old('title_en', $lecture->title_en) }}" dir="ltr" style="text-align:start"></div>
        <div class="field"><label>{{ __('المدة (بالثواني)') }}</label><input name="duration_seconds" type="number" min="0" value="{{ old('duration_seconds', $lecture->duration_seconds ?? 0) }}"></div>
      </div>

      <div class="field"><label>{{ __('الوصف (عربي)') }}</label><textarea name="description_ar" rows="3">{{ old('description_ar', $lecture->description_ar) }}</textarea></div>

      <div class="field">
        <label>{{ __('رفع فيديو (mp4 / webm / mov) — يُخزَّن مشفَّراً في القرص الخاص') }}</label>
        <input name="video" type="file" accept="video/mp4,video/webm,video/quicktime,video/x-matroska">
        @if ($lecture->video_path)
          <span style="color:var(--ink-3);font-size:.8rem">{{ __('يوجد فيديو محفوظ — اترك الحقل فارغاً للإبقاء عليه.') }}</span>
        @endif
      </div>

      <div style="display:flex;gap:18px;flex-wrap:wrap">
        <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $lecture->is_published ?? true))> {{ __('منشور') }}</label>
        <label class="check"><input type="checkbox" name="is_preview" value="1" @checked(old('is_preview', $lecture->is_preview ?? false))> {{ __('معاينة مجانية') }}</label>
      </div>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('instructor.lectures.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
