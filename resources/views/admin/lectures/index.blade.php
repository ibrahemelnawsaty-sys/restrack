@extends('layouts.app')

@section('title', __('المحاضرات — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div><h1>{{ __('المحاضرات') }}</h1><p>{{ __('أضِف، أعِد التسمية، وأعِد الترتيب كما تظهر للطلاب.') }}</p></div>
      <a class="btn btn-gold" href="{{ route('admin.lectures.create', ['level' => $levelId]) }}"><span class="sheen"></span>{{ __('محاضرة جديدة') }}</a>
    </div>

    <form method="GET" class="rise in" style="margin-bottom:16px">
      <div class="field" style="max-width:320px">
        <label>{{ __('المستوى') }}</label>
        <select name="level" onchange="this.form.submit()">
          @foreach ($levels as $l)
            <option value="{{ $l->id }}" @selected($levelId == $l->id)>{{ $l->name_ar }}</option>
          @endforeach
        </select>
      </div>
    </form>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>{{ __('الترتيب') }}</th><th>{{ __('العنوان') }}</th><th>{{ __('المدة') }}</th><th>{{ __('معاينة') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
          <tbody>
            @forelse ($lectures as $lec)
              <tr>
                <td style="display:flex;gap:4px;align-items:center">
                  <span class="num">{{ $lec->sort_order }}</span>
                  <form method="POST" action="{{ route('admin.lectures.move', ['lecture' => $lec, 'dir' => 'up']) }}">@csrf<button class="tbtn" style="padding:.2rem .4rem" title="{{ __('أعلى') }}">▲</button></form>
                  <form method="POST" action="{{ route('admin.lectures.move', ['lecture' => $lec, 'dir' => 'down']) }}">@csrf<button class="tbtn" style="padding:.2rem .4rem" title="{{ __('أسفل') }}">▼</button></form>
                </td>
                <td>{{ $lec->title_ar }}</td>
                <td class="num">{{ $lec->duration_label }}</td>
                <td>@if ($lec->is_preview)<span class="badge ok">{{ __('مجانية') }}</span>@else<span class="badge muted">{{ __('مدفوعة') }}</span>@endif</td>
                <td>@if ($lec->is_published)<span class="badge ok">{{ __('منشور') }}</span>@else<span class="badge muted">{{ __('مسودّة') }}</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.lectures.edit', $lec) }}">{{ __('تعديل') }}</a>
                  <form method="POST" action="{{ route('admin.lectures.destroy', $lec) }}" onsubmit="return confirm('حذف المحاضرة؟')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button></form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="color:var(--ink-3)">{{ __('لا توجد محاضرات لهذا المستوى.') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
