@extends('layouts.app')

@section('title', __('المستخدمون — الإدارة'))

@section('content')
  <section class="page">
    @include('admin._nav')

    <div class="page-head rise in"><h1>{{ __('المستخدمون') }}</h1><p>{{ __('إدارة الأدوار (طالب · سفير · مدرّب · مدير).') }}</p></div>

    <form method="GET" class="rise in" style="margin-bottom:16px">
      <div class="field" style="max-width:360px;margin-bottom:0">
        <input name="q" value="{{ request('q') }}" placeholder="{{ __('ابحث بالاسم أو البريد…') }}">
      </div>
    </form>

    <div class="glass tile rise in">
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>{{ __('الاسم') }}</th><th>{{ __('البريد') }}</th><th>{{ __('الدور') }}</th><th>{{ __('تغيير الدور') }}</th></tr></thead>
          <tbody>
            @foreach ($users as $u)
              <tr>
                <td>{{ $u->name }}</td>
                <td style="direction:ltr;text-align:start">{{ $u->email }}</td>
                <td><span class="badge muted">{{ $roles[$u->role] ?? $u->role }}</span></td>
                <td>
                  <form method="POST" action="{{ route('admin.users.role', $u) }}" style="display:flex;gap:6px;align-items:center">
                    @csrf @method('PATCH')
                    <select name="role" style="padding:.4rem .6rem;border-radius:10px;background:var(--g-fill-2);border:1px solid var(--g-border);color:var(--ink);font-family:inherit">
                      @foreach ($roles as $key => $label)<option value="{{ $key }}" @selected($u->role === $key)>{{ $label }}</option>@endforeach
                    </select>
                    <button class="btn btn-ghost btn-sm">{{ __('حفظ') }}</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div style="margin-top:16px">{{ $users->links() }}</div>
  </section>
@endsection
