@extends('layouts.app')

@section('title', 'محاضراتي — بوّابة المدرّب')

@section('content')
  <section class="page">
    @include('instructor._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
      <h1>محاضراتي</h1>
      <a class="btn btn-gold btn-sm" href="{{ route('instructor.lectures.create') }}"><span class="sheen"></span>محاضرة جديدة</a>
    </div>

    @if (session('status'))
      <div class="flash rise in" role="status">{{ session('status') }}</div>
    @endif

    <div class="glass tile rise in">
      @if ($lectures->isEmpty())
        <p style="color:var(--ink-2)">لا محاضرات بعد.</p>
      @else
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>الترتيب</th><th>العنوان</th><th>المستوى</th><th>المدة</th><th>فيديو</th><th>الحالة</th><th></th></tr></thead>
            <tbody>
              @foreach ($lectures as $lec)
                <tr>
                  <td class="num">{{ $lec->sort_order }}</td>
                  <td>{{ $lec->title_ar }}</td>
                  <td>{{ $lec->level?->name_ar }}</td>
                  <td class="num">{{ $lec->duration_label }}</td>
                  <td>{{ $lec->video_path ? 'نعم' : '—' }}</td>
                  <td><span class="badge {{ $lec->is_published ? '' : 'muted' }}">{{ $lec->is_published ? 'منشورة' : 'مسودّة' }}</span></td>
                  <td><a href="{{ route('instructor.lectures.edit', $lec) }}" style="color:var(--violet);font-weight:700">تعديل</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </section>
@endsection
