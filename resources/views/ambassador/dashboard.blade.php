@extends('layouts.app')

@section('title', __('لوحة السفير — Restrack'))

@section('content')
  <section class="page">
    <nav class="glass tile rise in" style="display:flex;gap:6px;flex-wrap:wrap;padding:10px;margin-bottom:20px;align-items:center">
      <span class="btn btn-gold-line btn-sm">{{ __('لوحة السفير') }}</span>
      <a href="{{ route('home') }}" style="margin-inline-start:auto;padding:.5rem .8rem;text-decoration:none;color:var(--ink-3);font-weight:700;font-size:.82rem">{{ __('← الموقع') }}</a>
    </nav>

    <div class="page-head rise in">
      <span class="eyebrow"><svg class="ico" aria-hidden="true"><use href="#i-users"/></svg>{{ __('سفير ريستراك') }}</span>
      <h1>مرحباً، {{ $user->name }}</h1>
      <p>{{ __('شارك رابط دعوتك مع طلابك — كل من يسجّل عبره يُنسَب إليك، وتتابع أعدادهم من هنا.') }}</p>
    </div>

    <div class="glass tile rise in">
      <h3 style="margin-bottom:6px">{{ __('رابط الدعوة الخاص بك') }}</h3>
      <p style="color:var(--ink-2);font-size:.9rem;margin-bottom:12px">{{ __('انسخه وأرسله لطلابك عبر واتساب أو غيره.') }}</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <input id="refUrl" value="{{ $referral['url'] }}" readonly dir="ltr" onclick="this.select()"
               style="flex:1;min-width:220px;padding:.6rem .8rem;border-radius:10px;border:1px solid var(--g-border);background:var(--g-fill-2);color:var(--ink);text-align:start;font-size:.85rem">
        <button type="button" class="btn btn-gold-line btn-sm" onclick="navigator.clipboard&&navigator.clipboard.writeText(document.getElementById('refUrl').value);this.textContent='نُسِخ'">{{ __('نسخ الرابط') }}</button>
      </div>
      <div class="stat-row" style="margin-top:14px">
        <div class="glass stat"><div class="v num">{{ $referral['registered'] }}</div><div class="k">{{ __('طالب سجّل عبرك') }}</div></div>
        <div class="glass stat"><div class="v num">{{ $referral['subscribers'] }}</div><div class="k">{{ __('مشترك فعّال') }}</div></div>
      </div>
    </div>

    <div class="glass tile rise in">
      <h3 style="margin-bottom:12px">{{ __('طلابك المسجّلون') }}</h3>
      @if ($students->isEmpty())
        <p style="color:var(--ink-2)">{{ __('لا طلاب بعد — انسخ رابطك وابدأ بمشاركته.') }}</p>
      @else
        <div class="table-wrap">
          <table class="tbl">
            <thead><tr><th>{{ __('الطالب') }}</th><th>{{ __('تاريخ التسجيل') }}</th><th>{{ __('الاشتراك') }}</th></tr></thead>
            <tbody>
              @foreach ($students as $s)
                @php($active = $s->subscriptions->first(fn ($sub) => $sub->isActive()))
                <tr>
                  <td>{{ $s->name }}</td>
                  <td class="num">{{ $s->created_at->format('Y-m-d') }}</td>
                  <td><span class="badge {{ $active ? 'ok' : 'muted' }}">{{ $active ? __('مشترك') : __('غير مشترك') }}</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </section>
@endsection
