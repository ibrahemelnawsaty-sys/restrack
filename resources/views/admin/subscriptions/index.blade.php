@extends('layouts.app')

@section('title', 'الاشتراكات — الإدارة')

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>الاشتراكات</h1><p>يمكنك التفعيل اليدوي (مثل التحويل البنكي).</p></div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>المستخدم</th><th>عبر (دكتور)</th><th>الخطة</th><th>المبلغ</th><th>الحالة</th><th>ينتهي</th><th></th></tr></thead>
          <tbody>
            @foreach ($subscriptions as $sub)
              <tr>
                <td>{{ optional($sub->user)->name }}</td>
                <td>{{ optional(optional($sub->user)->referrer)->name ?? '—' }}</td>
                <td>{{ optional($sub->plan)->name_ar ?? '—' }}</td>
                <td class="num">{{ (int) $sub->amount }} ر.س</td>
                <td>
                  @php($cls = $sub->status === 'active' ? 'ok' : ($sub->status === 'pending' ? 'warn' : 'muted'))
                  <span class="badge {{ $cls }}">{{ $sub->status }}</span>
                </td>
                <td class="num">{{ optional($sub->expires_at)->format('Y-m-d') ?? '—' }}</td>
                <td>
                  @if ($sub->status !== 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.activate', $sub) }}">@csrf @method('PATCH')<button class="btn btn-gold-line btn-sm">تفعيل</button></form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div style="margin-top:16px">{{ $subscriptions->links() }}</div>
  </section>
@endsection
