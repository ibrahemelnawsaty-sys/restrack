@extends('layouts.app')

@section('title', ($guideline->exists ? __('تعديل') : __('إضافة')).' '.__('معيار').' — '.__('الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ $guideline->exists ? __('تعديل معيار') : __('معيار جديد') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $guideline->exists ? route('admin.guidelines.update', $guideline) : route('admin.guidelines.store') }}" enctype="multipart/form-data" class="glass tile rise in">
      @csrf
      @if ($guideline->exists) @method('PUT') @endif

      <div class="field"><label>{{ __('الاسم كما يُعرض') }}</label><input name="name_ar" value="{{ old('name_ar', $guideline->name_ar) }}" required placeholder="PRISMA"></div>
      <div class="field"><label>{{ __('Name (English) — اختياري') }}</label><input name="name_en" value="{{ old('name_en', $guideline->name_en) }}" dir="ltr" style="text-align:start"></div>

      <div class="field">
        <label>{{ __('المجموعة') }}</label>
        <select name="group_key" required>
          @foreach (\App\Models\Guideline::GROUPS as $g)
            <option value="{{ $g }}" @selected(old('group_key', $guideline->group_key) === $g)>{{ \App\Models\Guideline::groupLabel($g) }}</option>
          @endforeach
        </select>
      </div>

      <div class="field"><label>{{ __('يغطّي (يظهر كتلميح)') }}</label><input name="note_ar" value="{{ old('note_ar', $guideline->note_ar) }}" placeholder="{{ __('المراجعات المنهجية') }}"></div>

      <div class="field">
        <label>{{ __('الشعار') }}</label>
        <input type="file" name="logo_file" accept="image/*">
        <span style="color:var(--ink-3);font-size:.78rem">{{ __('اختياري — بدون شعار يُعرض اسم المعيار كنصّ أنيق. تأكّد من حقوق استخدام الشعار.') }}</span>
      </div>

      <div class="field" style="max-width:200px"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $guideline->sort_order ?? 0) }}"></div>

      <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $guideline->is_active ?? true))> {{ __('ظاهر') }}</label>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.guidelines.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
