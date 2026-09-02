@extends('layouts.app')

@section('title', __('المستويات — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div><h1>{{ __('المستويات') }}</h1><p>{{ __('أضِف وعدّل مستويات المسار.') }}</p></div>
      <a class="btn btn-gold" href="{{ route('admin.levels.create') }}"><span class="sheen"></span>{{ __('مستوى جديد') }}</a>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>{{ __('الاسم') }}</th><th>{{ __('المحاضرات') }}</th><th>{{ __('الأسئلة') }}</th><th>{{ __('النجاح') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
          <tbody>
            @foreach ($levels as $level)
              <tr>
                <td class="num">{{ $level->sort_order }}</td>
                <td>{{ $level->name }} @if ($level->name !== $level->name_en)<span style="color:var(--ink-3);font-size:.8rem;direction:ltr;unicode-bidi:isolate">· {{ $level->name_en }}</span>@endif</td>
                <td class="num">{{ $level->lectures_count }}</td>
                <td class="num">{{ $level->questions_count }}</td>
                <td class="num">{{ $level->pass_threshold }}%</td>
                <td>@if ($level->is_published)<span class="badge ok">{{ __('منشور') }}</span>@else<span class="badge muted">{{ __('مسودّة') }}</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.levels.edit', $level) }}">{{ __('تعديل') }}</a>
                  <form method="POST" action="{{ route('admin.levels.destroy', $level) }}" data-confirm="حذف المستوى وكل محتواه؟">
                    @csrf @method('DELETE')
                    <button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
