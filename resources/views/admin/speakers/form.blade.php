@extends('layouts.app')

@section('title', ($speaker->exists ? __('تعديل') : __('إضافة')).' '.__('متحدث').' — '.__('الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ $speaker->exists ? __('تعديل متحدث') : __('متحدث جديد') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $speaker->exists ? route('admin.speakers.update', $speaker) : route('admin.speakers.store') }}" enctype="multipart/form-data" class="glass tile rise in">
      @csrf
      @if ($speaker->exists) @method('PUT') @endif

      <div class="field"><label>{{ __('الاسم (عربي)') }}</label><input name="name_ar" value="{{ old('name_ar', $speaker->name_ar) }}" required placeholder="{{ __('د. خالد ...') }}"></div>
      <div class="field"><label>{{ __('Name (English) — اختياري') }}</label><input name="name_en" value="{{ old('name_en', $speaker->name_en) }}" dir="ltr" style="text-align:start"></div>
      <div class="field"><label>{{ __('التخصص (عربي)') }}</label><input name="credential_ar" value="{{ old('credential_ar', $speaker->credential_ar) }}" placeholder="{{ __('استشاري أمراض وراثية') }}"></div>
      <div class="field"><label>{{ __('Specialty (English) — اختياري') }}</label><input name="credential_en" value="{{ old('credential_en', $speaker->credential_en) }}" dir="ltr" style="text-align:start"></div>

      <div class="field">
        <label>{{ __('الإنجاز البارز (عربي)') }}</label>
        <input name="highlight_ar" value="{{ old('highlight_ar', $speaker->highlight_ar) }}" placeholder="{{ __('ناشر أكثر من 150 ورقة علمية') }}">
        <span style="color:var(--ink-3);font-size:.76rem">{{ __('ادعاء قابل للتحقّق — اكتبه فقط إذا كان موثّقاً. استخدم أرقاماً لاتينية.') }}</span>
      </div>
      <div class="field"><label>{{ __('Highlight (English) — اختياري') }}</label><input name="highlight_en" value="{{ old('highlight_en', $speaker->highlight_en) }}" dir="ltr" style="text-align:start"></div>

      <div class="field">
        <label>{{ __('الصورة') }}</label>
        <input type="file" name="avatar_file" accept="image/*">
        @if ($speaker->avatar)<span style="color:var(--ink-3);font-size:.78rem">{{ __('توجد صورة محفوظة — الرفع يستبدلها.') }}</span>@endif
      </div>

      <div class="field" style="max-width:200px"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $speaker->sort_order ?? 0) }}"></div>

      <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $speaker->is_active ?? true))> {{ __('ظاهر على الصفحة الرئيسية') }}</label>
      <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $speaker->is_featured ?? false))> {{ __('مميّز') }}</label>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.speakers.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
