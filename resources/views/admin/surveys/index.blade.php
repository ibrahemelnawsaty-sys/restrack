@extends('layouts.app')

@section('title', __('الاستبيانات — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <h1>{{ __('استبيانات المتعلّمين') }}</h1>
      <p>{{ __('المتوسّط من 5 لكل محور. هذه هي الأرقام التي يقوم عليها ادّعاء «التحسين المستمر» في قسم ضمان الجودة.') }}</p>
    </div>

    <div class="stat-row rise in">
      <div class="glass stat"><div class="v num">{{ $responses->count() }}</div><div class="k">{{ __('إجابة') }}</div></div>
      @foreach (\App\Models\SurveyResponse::AXES as $key => $label)
        <div class="glass stat"><div class="v num">{{ $responses->count() ? number_format($overall[$key], 1) : '—' }}</div><div class="k">{{ $label }}</div></div>
      @endforeach
    </div>

    <div class="glass tile rise in" style="margin-bottom:14px">
      <h3 style="margin-bottom:12px">{{ __('حسب المستوى') }}</h3>
      <div class="table-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>{{ __('المستوى') }}</th><th>{{ __('إجابات') }}</th>
              @foreach (\App\Models\SurveyResponse::AXES as $label)<th>{{ $label }}</th>@endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($levels as $level)
              <tr>
                <td>{{ $level->name_ar }}</td>
                <td class="num">{{ $byLevel[$level->id]['count'] }}</td>
                @foreach (array_keys(\App\Models\SurveyResponse::AXES) as $key)
                  <td class="num">{{ $byLevel[$level->id]['count'] ? number_format($byLevel[$level->id]['scores'][$key], 1) : '—' }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="glass tile rise in">
      <h3 style="margin-bottom:12px">{{ __('المقترحات المكتوبة') }}</h3>
      @forelse ($responses->whereNotNull('notes')->where('notes', '!=', '') as $r)
        <div style="border-top:1px solid var(--g-border);padding-top:12px;margin-top:12px">
          <b style="font-size:.9rem">{{ $r->user?->name }}</b>
          <span class="badge muted" style="margin-inline-start:6px">{{ $r->level?->name_ar ?? __('المسار') }}</span>
          <p style="color:var(--ink-2);font-size:.88rem;margin-top:6px;white-space:pre-line">{{ $r->notes }}</p>
        </div>
      @empty
        <p style="color:var(--ink-3);font-size:.88rem">{{ __('لا توجد مقترحات مكتوبة بعد.') }}</p>
      @endforelse
    </div>
  </section>
@endsection
