@extends('layouts.app')

@section('title', 'محتوى الصفحات — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <h1>محتوى الصفحات</h1>
      <p>حرّر نصوص الموقع (العنوان، الوصف…) بالعربية والإنجليزية — تُحدَّث فوراً.</p>
    </div>

    <form method="POST" action="{{ route('admin.content.update') }}" class="glass tile rise in">
      @csrf
      @method('PUT')
      <div style="display:grid;gap:18px">
        @foreach ($sections as $s)
          <div style="border-top:1px solid var(--g-border);padding-top:14px">
            <div class="crumb" style="margin-bottom:8px"><b>{{ $s->page }}</b><span>/</span><span>{{ $s->section }}</span><span>/</span><span style="color:var(--gold-ink)">{{ $s->item_key }}</span></div>
            <div class="field">
              <label>النص (عربي)</label>
              <textarea name="rows[{{ $s->id }}][value_ar]" rows="2">{{ $s->value_ar }}</textarea>
            </div>
            <div class="field" style="margin-bottom:0">
              <label>Text (English)</label>
              <textarea name="rows[{{ $s->id }}][value_en]" rows="2" dir="ltr" style="text-align:start">{{ $s->value_en }}</textarea>
            </div>
          </div>
        @endforeach
      </div>
      <button type="submit" class="btn btn-gold" style="margin-top:18px"><span class="sheen"></span>حفظ المحتوى</button>
    </form>
  </section>
@endsection
