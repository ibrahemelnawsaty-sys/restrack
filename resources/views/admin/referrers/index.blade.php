@extends('layouts.app')

@section('title', 'الدكاترة والإحالات — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-users"/></svg>الدكاترة والإحالات</span>
      <h1>الدكاترة والمشتركون عبرهم</h1>
      <p>القائمة تظهر للطالب عند التسجيل. أضِف دكاترة بلا حساب هنا. إجمالي المسجّلين عبر إحالة: <b class="num">{{ $total }}</b>.</p>
    </div>

    @if (session('status'))
      <div class="flash rise in" role="status">{{ session('status') }}</div>
    @endif

    <div class="glass tile rise in" style="margin-bottom:18px">
      <h3 style="margin-bottom:10px">إضافة دكتور (بلا حساب)</h3>
      <form method="POST" action="{{ route('admin.referrers.store') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        @csrf
        <input name="name" placeholder="اسم الدكتور" required
               style="flex:1;min-width:220px;padding:.55rem .8rem;border-radius:10px;border:1px solid var(--g-border);background:var(--g-fill-2);color:var(--ink);font-family:inherit">
        <button class="btn btn-gold btn-sm"><span class="sheen"></span>إضافة</button>
      </form>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>الدكتور</th><th>النوع</th><th>مسجّلون</th><th>مشتركون فعّالون</th><th>رابط الدعوة</th><th>الحالة / إجراءات</th></tr></thead>
          <tbody>
            @forelse ($referrers as $r)
              <tr>
                <td>
                  <form method="POST" action="{{ route('admin.referrers.update', $r) }}" id="rf{{ $r->id }}" style="display:flex;gap:6px;align-items:center">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $r->name }}" style="min-width:150px;padding:.4rem .6rem;border-radius:8px;border:1px solid var(--g-border);background:var(--g-fill-2);color:var(--ink);font-family:inherit">
                  </form>
                </td>
                <td>@if ($r->user_id)<span class="badge">حساب</span>@else<span class="badge muted">بلا حساب</span>@endif</td>
                <td class="num">{{ $r->referred_users_count }}</td>
                <td class="num"><span class="badge {{ $r->subscribers_count ? 'ok' : 'muted' }}">{{ $r->subscribers_count }}</span></td>
                <td style="direction:ltr;text-align:start;font-size:.74rem;color:var(--ink-3)">{{ $r->referralUrl() }}</td>
                <td>
                  <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                    <label class="check" style="font-size:.8rem"><input type="checkbox" name="is_active" value="1" form="rf{{ $r->id }}" @checked($r->is_active)> فعّال</label>
                    <button class="btn btn-ghost btn-sm" form="rf{{ $r->id }}">حفظ</button>
                    <form method="POST" action="{{ route('admin.referrers.destroy', $r) }}" onsubmit="return confirm('حذف {{ $r->name }}؟')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm" style="color:#F0506E;background:transparent;border:1px solid var(--g-border)">حذف</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="color:var(--ink-2)">لا دكاترة بعد — أضِف أول اسم من الأعلى.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
