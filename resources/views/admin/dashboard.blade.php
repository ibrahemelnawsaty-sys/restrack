@extends('layouts.app')

@section('title', 'لوحة الإدارة — Restrack')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg>مركز التحكّم</span>
      <h1>لوحة الإدارة</h1>
      <p>نظرة عامة سريعة على المنصة.</p>
    </div>

    <div class="stat-row rise in">
      <div class="glass stat"><div class="v num">{{ $stats['students'] }}</div><div class="k">طالب</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['active_subs'] }}</div><div class="k">اشتراك فعّال</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['lectures'] }}</div><div class="k">محاضرة</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['questions'] }}</div><div class="k">سؤال</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['certificates'] }}</div><div class="k">شهادة</div></div>
      <div class="glass stat"><div class="v num">{{ $stats['levels'] }}</div><div class="k">مستوى</div></div>
    </div>

    <div class="glass tile rise in">
      <h3 style="margin-bottom:12px">أحدث المستخدمين</h3>
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>الاسم</th><th>البريد</th><th>الدور</th><th>التسجيل</th></tr></thead>
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
