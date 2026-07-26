@extends('layouts.app')

@section('title', 'بوّابة المدرّب — Restrack')

@section('content')
  <section class="page">
    @include('instructor._nav')

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-research"/></svg>بوّابة المدرّب</span>
      <h1>مرحباً، {{ $speaker->name_ar }}</h1>
      <p>من هنا تدير محاضراتك وترفع فيديوهاتك — يظهر لك محتواك أنت فقط.</p>
    </div>

    <div class="stat-row rise in">
      <div class="glass stat"><div class="v num">{{ $stats['lectures'] }}</div><div class="k">محاضرة</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['published'] }}</div><div class="k">منشورة</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['levels'] }}</div><div class="k">مستوى</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['minutes'] }}</div><div class="k">دقيقة محتوى</div></div>
    </div>

    <div class="glass tile rise in">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;flex-wrap:wrap">
        <h3>أحدث محاضراتك</h3>
        <a class="btn btn-gold btn-sm" href="{{ route('instructor.lectures.create') }}"><span class="sheen"></span>محاضرة جديدة</a>
      </div>

      @if ($lectures->isEmpty())
        <p style="color:var(--ink-2)">لا محاضرات بعد — ابدأ بإضافة أولى محاضراتك ورفع فيديوها.</p>
      @else
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>العنوان</th><th>المستوى</th><th>المدة</th><th>الحالة</th><th></th></tr></thead>
            <tbody>
              @foreach ($lectures->take(8) as $lec)
                <tr>
                  <td>{{ $lec->title_ar }}</td>
                  <td>{{ $lec->level?->name_ar }}</td>
                  <td class="num">{{ $lec->duration_label }}</td>
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
