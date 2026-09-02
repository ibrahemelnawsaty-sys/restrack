@extends('layouts.app')

@section('title', __('الخطط — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div><h1>{{ __('خطط الاشتراك') }}</h1><p>{{ __('الأسعار شاملة ضريبة القيمة المضافة 15%.') }}</p></div>
      <a class="btn btn-gold" href="{{ route('admin.plans.create') }}"><span class="sheen"></span>{{ __('خطة جديدة') }}</a>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>{{ __('الخطة') }}</th><th>{{ __('السعر') }}</th><th>{{ __('الدورة') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
          <tbody>
            @foreach ($plans as $plan)
              <tr>
                <td>{{ $plan->name }} @if ($plan->is_featured)<span class="badge ok">{{ __('مميّزة') }}</span>@endif</td>
                <td class="num">{{ (int) $plan->price }} ر.س</td>
                <td>{{ $plan->interval === 'annual' ? __('سنوي') : __('شهري') }}</td>
                <td>@if ($plan->is_active)<span class="badge ok">{{ __('فعّالة') }}</span>@else<span class="badge muted">{{ __('موقوفة') }}</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.plans.edit', $plan) }}">{{ __('تعديل') }}</a>
                  <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" data-confirm="حذف الخطة؟">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button></form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
