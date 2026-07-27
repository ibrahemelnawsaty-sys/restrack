@extends('layouts.app')

@section('title', 'الإحالات — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-users"/></svg>الإحالات</span>
      <h1>المشتركون حسب الدكتور</h1>
      <p>لكل دكتور رابط دعوة؛ من يسجّل عبره يُنسَب إليه. إجمالي المسجّلين عبر إحالة: <b class="num">{{ $totalReferred }}</b>.</p>
    </div>

    <div class="glass tile rise in">
      @if ($doctors->isEmpty())
        <p style="color:var(--ink-2)">لا يوجد مدرّبون بعد. أضِف مدرّباً من «المستخدمون» بترقية دوره.</p>
      @else
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>الدكتور</th><th>الكود</th><th>مسجّلون</th><th>مشتركون فعّالون</th><th>رابط الدعوة</th></tr></thead>
            <tbody>
              @foreach ($doctors as $d)
                <tr>
                  <td>{{ $d->name }}</td>
                  <td class="num" style="direction:ltr;text-align:start">{{ $d->referral_code }}</td>
                  <td class="num">{{ $d->referrals_count }}</td>
                  <td class="num"><span class="badge {{ $d->subscribers_count ? 'ok' : 'muted' }}">{{ $d->subscribers_count }}</span></td>
                  <td style="direction:ltr;text-align:start;font-size:.76rem;color:var(--ink-3)">{{ $d->referralUrl() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </section>
@endsection
