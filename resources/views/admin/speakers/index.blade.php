@extends('layouts.app')

@section('title', __('المتحدثون — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div>
        <h1>{{ __('المتحدثون') }}</h1>
        <p>{{ __('يظهرون في قسم «متحدثونا» على الصفحة الرئيسية. لا تُدرِج رقماً (كعدد الأوراق المنشورة) إلا إذا كان موثّقاً.') }}</p>
      </div>
      <a class="btn btn-gold" href="{{ route('admin.speakers.create') }}"><span class="sheen"></span>{{ __('متحدث جديد') }}</a>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>{{ __('الاسم') }}</th><th>{{ __('التخصص') }}</th><th>{{ __('الإنجاز') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
          <tbody>
            @forelse ($speakers as $speaker)
              <tr>
                <td class="num">{{ $speaker->sort_order }}</td>
                <td>{{ $speaker->name }}</td>
                <td style="white-space:normal">{{ $speaker->credential ?: $speaker->title }}</td>
                <td style="white-space:normal">{{ $speaker->highlight ?: '—' }}</td>
                <td>@if ($speaker->is_active)<span class="badge ok">{{ __('ظاهر') }}</span>@else<span class="badge muted">{{ __('مخفي') }}</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.speakers.edit', $speaker) }}">{{ __('تعديل') }}</a>
                  <form method="POST" action="{{ route('admin.speakers.destroy', $speaker) }}" data-confirm="{{ __('حذف المتحدث؟') }}">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button></form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="color:var(--ink-3);white-space:normal">{{ __('لا يوجد متحدثون بعد — القسم لا يُعرض على الصفحة الرئيسية حتى تُضيف أول متحدث.') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
