@extends('layouts.app')

@section('title', __('محاضراتي — بوّابة المدرّب'))

@section('content')
  <section class="page">
    @include('instructor._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
      <h1>{{ __('محاضراتي') }}</h1>
      <a class="btn btn-gold btn-sm" href="{{ route('instructor.lectures.create') }}"><span class="sheen"></span>{{ __('محاضرة جديدة') }}</a>
    </div>

    @if (session('status'))
      <div class="flash rise in" role="status">{{ session('status') }}</div>
    @endif

    <div class="glass tile rise in">
      @if ($lectures->isEmpty())
        <p style="color:var(--ink-2)">{{ __('لا محاضرات بعد.') }}</p>
      @else
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>{{ __('الترتيب') }}</th><th>{{ __('العنوان') }}</th><th>{{ __('المستوى') }}</th><th>{{ __('المدة') }}</th><th>{{ __('فيديو') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
            <tbody>
              @foreach ($lectures as $lec)
                <tr>
                  <td class="num">{{ $lec->sort_order }}</td>
                  <td>{{ $lec->title_ar }}</td>
                  <td>{{ $lec->level?->name_ar }}</td>
                  <td class="num">{{ $lec->duration_label }}</td>
                  <td>{{ $lec->video_path ? __('نعم') : '—' }}</td>
                  <td><span class="badge {{ $lec->is_published ? '' : 'muted' }}">{{ $lec->is_published ? __('منشورة') : __('مسودّة') }}</span></td>
                  <td><a href="{{ route('instructor.lectures.edit', $lec) }}" style="color:var(--violet);font-weight:700">{{ __('تعديل') }}</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </section>
@endsection
