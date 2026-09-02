@extends('layouts.app')

@section('title', __('المعايير — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div>
        <h1>{{ __('المعايير التي نلتزم بها') }}</h1>
        <p>{{ __('تظهر كشريط شعارات أعلى الصفحة الرئيسية وكأربع مجموعات في قسم المعايير. الشعار اختياري — بدونه يُعرض اسم المعيار.') }}</p>
      </div>
      <a class="btn btn-gold" href="{{ route('admin.guidelines.create') }}"><span class="sheen"></span>{{ __('معيار جديد') }}</a>
    </div>

    @foreach (\App\Models\Guideline::GROUPS as $group)
      @continue (empty($guidelines[$group]))
      <div class="glass tile rise in" style="margin-bottom:14px">
        <h3 style="margin-bottom:12px">{{ \App\Models\Guideline::groupLabel($group) }}</h3>
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>#</th><th>{{ __('الاسم') }}</th><th>{{ __('يغطّي') }}</th><th>{{ __('الشعار') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
            <tbody>
              @foreach ($guidelines[$group] as $g)
                <tr>
                  <td class="num">{{ $g->sort_order }}</td>
                  <td><bdi>{{ $g->name }}</bdi></td>
                  <td style="white-space:normal">{{ $g->note_ar ?: '—' }}</td>
                  <td>@if ($g->logo)<span class="badge ok">{{ __('مرفوع') }}</span>@else<span class="badge muted">{{ __('نصّي') }}</span>@endif</td>
                  <td>@if ($g->is_active)<span class="badge ok">{{ __('ظاهر') }}</span>@else<span class="badge muted">{{ __('مخفي') }}</span>@endif</td>
                  <td style="display:flex;gap:6px">
                    <a class="btn btn-ghost btn-sm" href="{{ route('admin.guidelines.edit', $g) }}">{{ __('تعديل') }}</a>
                    <form method="POST" action="{{ route('admin.guidelines.destroy', $g) }}" data-confirm="{{ __('حذف المعيار؟') }}">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button></form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
  </section>
@endsection
