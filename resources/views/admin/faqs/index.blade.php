@extends('layouts.app')

@section('title', __('الأسئلة الشائعة — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div><h1>{{ __('الأسئلة الشائعة') }}</h1></div>
      <a class="btn btn-gold" href="{{ route('admin.faqs.create') }}"><span class="sheen"></span>{{ __('سؤال جديد') }}</a>
    </div>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>{{ __('السؤال') }}</th><th>{{ __('الحالة') }}</th><th></th></tr></thead>
          <tbody>
            @foreach ($faqs as $faq)
              <tr>
                <td class="num">{{ $faq->sort_order }}</td>
                <td style="white-space:normal">{{ $faq->question }}</td>
                <td>@if ($faq->is_published)<span class="badge ok">{{ __('منشور') }}</span>@else<span class="badge muted">{{ __('مخفي') }}</span>@endif</td>
                <td style="display:flex;gap:6px">
                  <a class="btn btn-ghost btn-sm" href="{{ route('admin.faqs.edit', $faq) }}">{{ __('تعديل') }}</a>
                  <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" data-confirm="حذف السؤال؟">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:#F0506E">{{ __('حذف') }}</button></form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
