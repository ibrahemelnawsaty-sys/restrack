@extends('layouts.app')

@section('title', 'إنشاء حساب — Restrack')

@section('content')
  <div class="auth-wrap">
    <div class="glass auth-card rise in">
      <h1>ابدأ رحلتك</h1>
      <p class="sub">أنشئ حسابك المجاني وابدأ من المستوى الأول.</p>

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field">
          <label for="name">الاسم الكامل</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
          @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="email">البريد الإلكتروني</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required dir="ltr" style="text-align:start">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="password">كلمة المرور</label>
          <input id="password" name="password" type="password" required>
          @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="password_confirmation">تأكيد كلمة المرور</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>
        <button type="submit" class="btn btn-gold full" style="margin-top:18px"><span class="sheen"></span>إنشاء الحساب</button>
      </form>

      <p class="form-foot">لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
    </div>
  </div>
@endsection
