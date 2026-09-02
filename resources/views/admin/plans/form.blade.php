@extends('layouts.app')

@section('title', ($plan->exists ? 'تعديل' : 'إضافة').' خطة — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ $plan->exists ? __('تعديل خطة') : __('خطة جديدة') }}</h1></div>

    @if ($errors->any())
      <div class="alert rise in" style="color:#F0506E">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" class="glass tile rise in">
      @csrf
      @if ($plan->exists) @method('PUT') @endif

      <div class="split" style="grid-template-columns:1fr 1fr">
        <div class="field"><label>{{ __('الاسم (عربي)') }}</label><input name="name_ar" value="{{ old('name_ar', $plan->name_ar) }}" required></div>
        <div class="field"><label>Name (English)</label><input name="name_en" value="{{ old('name_en', $plan->name_en) }}" dir="ltr" style="text-align:start"></div>
        <div class="field"><label>{{ __('المُعرّف (slug)') }}</label><input name="slug" value="{{ old('slug', $plan->slug) }}" dir="ltr" style="text-align:start" required></div>
        <div class="field"><label>{{ __('السعر (ر.س)') }}</label><input name="price" type="number" step="0.01" min="0" value="{{ old('price', $plan->price ?? 0) }}" required></div>
        <div class="field"><label>{{ __('الدورة') }}</label><select name="interval"><option value="monthly" @selected(old('interval', $plan->interval) === 'monthly')>{{ __('شهري') }}</option><option value="annual" @selected(old('interval', $plan->interval) === 'annual')>{{ __('سنوي') }}</option></select></div>
        <div class="field"><label>{{ __('الترتيب') }}</label><input name="sort_order" type="number" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"></div>
      </div>

      <div class="field">
        <label>{{ __('المميّزات (سطر لكل ميزة)') }}</label>
        <textarea name="features_ar" rows="5">{{ old('features_ar', implode("\n", $plan->features_ar ?? [])) }}</textarea>
        <span style="color:var(--ink-3);font-size:.76rem">{{ __('اللصق من العرض التقديمي مقبول — الفاصل • أو · يبدأ ميزة جديدة، إلا داخل الأقواس.') }}</span>
      </div>
      <div class="field"><label>{{ __('المميّزات (إنجليزي)') }}</label><textarea name="features_en" rows="5" dir="ltr" style="text-align:start">{{ old('features_en', implode("\n", $plan->features_en ?? [])) }}</textarea></div>

      <div style="display:flex;gap:18px;flex-wrap:wrap">
        <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))> {{ __('فعّالة') }}</label>
        <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $plan->is_featured ?? false))> {{ __('مميّزة (الأوفر)') }}</label>
      </div>

      <div style="margin-top:18px;display:flex;gap:8px">
        <button type="submit" class="btn btn-gold"><span class="sheen"></span>{{ __('حفظ') }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.plans.index') }}">{{ __('إلغاء') }}</a>
      </div>
    </form>
  </section>
@endsection
