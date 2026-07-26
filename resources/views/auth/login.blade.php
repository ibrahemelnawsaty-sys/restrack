@extends('layouts.app')

@section('title', 'تسجيل الدخول — Restrack')

@section('content')
  <div class="auth-wrap">
    <div class="glass auth-card rise in">
      <h1>تسجيل الدخول</h1>
      <p class="sub">أهلاً بعودتك — تابع رحلتك في البحث الطبي.</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
          <label for="email">البريد الإلكتروني</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus dir="ltr" style="text-align:start">
          @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label for="password">كلمة المرور</label>
          <input id="password" name="password" type="password" required>
          @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <label class="check"><input type="checkbox" name="remember"> تذكّرني على هذا الجهاز</label>
        <button type="submit" class="btn btn-gold full" style="margin-top:18px"><span class="sheen"></span>دخول</button>
      </form>

      <p class="form-foot">ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ حساباً مجاناً</a></p>
    </div>
  </div>
@endsection
