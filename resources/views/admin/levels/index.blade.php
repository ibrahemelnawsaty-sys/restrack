@extends('layouts.app')

@section('title', 'المستويات — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div><h1>المستويات</h1><p>أضِف وعدّل مستويات المسار.</p></div>
      <a class="btn btn-gold" href="{{ route('admin.levels.create') }}"><span class="sheen"></span>مستوى جديد</a>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>الاسم</th><th>المحاضرات</th><th>الأسئلة</th><th>النجاح</th><th>الحالة</th><th></th></tr></thead>
          <tbody>
            @foreach ($levels as $level)
              <tr>
                <td class="num">{{ $level->sort_order }}</td>
                <td>{{ $level->name_ar }} <span style="color:var(--ink-3);font-size:.8rem;direction:ltr;unicode-bidi:isolate">· {{ $level->name_en }}</span></td>
                <td class="num">{{ $level->lectures_count }}</td>
                <td class="num">{{ $level->questions_count }}</td>
                <td class="num">{{ $level->pass_threshold }}%</td>
                <td>@if ($level->is_published)<span class="badge ok">منشور</span>@else<span class="badge muted">مسودّة</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.levels.edit', $level) }}">تعديل</a>
                  <form method="POST" action="{{ route('admin.levels.destroy', $level) }}" onsubmit="return confirm('حذف المستوى وكل محتواه؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-ghost btn-sm" style="color:#F0506E">حذف</button>
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
