@extends('layouts.app')

@section('title', __('لوحة الإدارة — Restrack'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg>{{ __('مركز التحكّم') }}</span>
      <h1>{{ __('لوحة الإدارة') }}</h1>
      <p>{{ __('نظرة عامة سريعة على المنصة.') }}</p>
    </div>

    <div class="stat-row rise in">
      <div class="glass stat"><div class="v num">{{ $stats['students'] }}</div><div class="k">{{ __('طالب') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['active_subs'] }}</div><div class="k">{{ __('اشتراك فعّال') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['lectures'] }}</div><div class="k">{{ __('محاضرة') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['questions'] }}</div><div class="k">{{ __('سؤال') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['certificates'] }}</div><div class="k">{{ __('شهادة') }}</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['levels'] }}</div><div class="k">{{ __('مستوى') }}</div></div>
    </div>

    <div class="glass tile rise in">
      <h3 style="margin-bottom:12px">{{ __('أحدث المستخدمين') }}</h3>
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>{{ __('الاسم') }}</th><th>{{ __('البريد') }}</th><th>{{ __('الدور') }}</th><th>{{ __('التسجيل') }}</th></tr></thead>
          <tbody>
            @foreach ($recentUsers as $u)
              <tr>
                <td>{{ $u->name }}</td>
                <td style="direction:ltr;text-align:start">{{ $u->email }}</td>
                <td><span class="badge muted">{{ $u->role }}</span></td>
                <td class="num">{{ $u->created_at->format('Y-m-d') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
